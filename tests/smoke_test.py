#!/usr/bin/env python3
"""Dependency-free PHP functional smoke test for Lab 6."""
import contextlib
import http.cookiejar
import os
from pathlib import Path
import re
import socket
import subprocess
import sys
import time
import urllib.error
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]
assert (ROOT / 'index.php').is_file()

def test():
    passes = []
    backed_up = {}
    for name in ('comments.jsonl', 'submissions.jsonl'):
        path = ROOT / 'data' / name
        backed_up[path] = path.read_bytes() if path.exists() else None
    for file in sorted(ROOT.rglob('*.php')):
        run = subprocess.run(['php','-l',str(file)], text=True, capture_output=True)
        assert run.returncode == 0, run.stdout + run.stderr
        passes.append('PHP syntax: ' + str(file.relative_to(ROOT)))
    with socket.socket() as s:
        s.bind(('127.0.0.1',0)); port=s.getsockname()[1]
    proc=subprocess.Popen(['php','-S',f'127.0.0.1:{port}','-t',str(ROOT)],cwd=ROOT,stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL)
    jar=http.cookiejar.CookieJar()
    opener=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))
    url=f'http://127.0.0.1:{port}'
    def request(path,data=None):
        body=None if data is None else urllib.parse.urlencode(data,doseq=True).encode('utf-8')
        with opener.open(url+path,body,timeout=8) as res:
            return res.status,res.geturl(),res.read().decode('utf-8')
    try:
        for _ in range(40):
            try:
                status,_,h=request('/index.php');break
            except Exception:time.sleep(.1)
        else:raise AssertionError('PHP server did not start')
        assert status==200 and 'Bài thực hành Lab 6' in h
        passes.append('Homepage returns 200 and links to exercises')
        for i in range(1,6):
            assert f'ex{i}.php' in h
        passes.append('Homepage links to all five exercises')
        status,_,h=request('/ex1.php')
        assert status==200 and h.count('<td>')==100 and '10 x 10 = 100' in h and '1 x 1 = 1' in h
        passes.append('Exercise 1: exactly 100 multiplication cells')
        status,_,h=request('/ex2.php')
        assert status==200 and 'Tính toán cơ bản' in h
        passes.append('Exercise 2: calculator form rendered')
        _,_,h=request('/ex2.php',{'num1':'','num2':'','operation':'add'})
        assert 'Vui lòng nhập đầy đủ' in h
        passes.append('Exercise 2: missing input error')
        _,_,h=request('/ex2.php',{'num1':'3','num2':'2','operation':'multiply'})
        assert '3 × 2 = 6' in h
        passes.append('Exercise 2: multiplication returns correct PHP result')
        _,_,h=request('/ex2.php',{'num1':'3','num2':'2','operation':'subtract'})
        assert '3 - 2 = 1' in h
        passes.append('Exercise 2: subtraction returns correct result')
        _,_,h=request('/ex2.php',{'num1':'1','num2':'0','operation':'divide'})
        assert 'Không thể chia cho 0' in h
        passes.append('Exercise 2: division by zero rejected')
        _,_,h=request('/ex2.php',{'num1':'<script>alert(1)</script>','num2':'2','operation':'add'})
        assert '&lt;script&gt;' in h and '<script>alert(1)</script>' not in h
        passes.append('Exercise 2: unsafe input HTML-escaped')
        _,_,h=request('/ex3.php');csrf=re.search(r'name="csrf" value="([a-f0-9]+)"',h).group(1)
        assert 'Chưa có bình luận' in h or 'Gửi bình luận' in h
        passes.append('Exercise 3: comments form and CSRF token rendered')
        _,redirect,h=request('/ex3.php',{'action':'add','csrf':csrf,'name':'Alice','comment':'First comment','type':'alert-primary'})
        assert 'Alice' in h and 'First comment' in h and 'status=added' in redirect
        passes.append('Exercise 3: adds and persists first comment')
        _,_,h=request('/ex3.php',{'action':'add','csrf':csrf,'name':'Bob','comment':'Second comment','type':'alert-success'})
        assert h.index('Second comment') < h.index('First comment')
        passes.append('Exercise 3: newest comment first')
        raw=(ROOT/'data/comments.jsonl').read_text(encoding='utf-8')
        assert 'Alice' in raw and 'Bob' in raw
        passes.append('Exercise 3: append JSONL data stored on disk')
        _,_,h=request('/ex3.php',{'action':'add','csrf':csrf,'name':'Mallory','comment':'<script>alert(1)</script>','type':'alert-danger'})
        assert '&lt;script&gt;alert(1)&lt;/script&gt;' in h and '<script>alert(1)</script>' not in h
        passes.append('Exercise 3: comment XSS escaped')
        _,_,h=request('/ex3.php',{'action':'add','csrf':'wrong','name':'Eve','comment':'bad','type':'alert-primary'})
        assert 'Phiên làm việc không hợp lệ' in h
        passes.append('Exercise 3: rejects invalid CSRF')
        ids=re.findall(r'name="id" value="([a-f0-9]{16})"',h)
        assert len(ids)>=3
        _,_,h=request('/ex3.php',{'action':'delete','csrf':csrf,'id':ids[0]})
        assert 'Mallory' not in h and 'Second comment' in h and 'First comment' in h
        passes.append('Exercise 3: removes exact comment from interface')
        assert 'Mallory' not in (ROOT/'data/comments.jsonl').read_text(encoding='utf-8')
        passes.append('Exercise 3: removes comment from file')
        _,_,h=request('/ex4.php')
        assert 'ex4-confirm.php' in h and 'browsers[]' in h
        passes.append('Exercise 4: template form posts to separate PHP page')
        _,redirect,h=request('/ex4-confirm.php',{'name':'Nguyễn Văn Linh','email':'linh@gmail.com','gender':'Male','browsers[]':['Google Chrome','Safari'],'os':'macOS'})
        assert 'Confirm Information' in h and 'Nguyễn Văn Linh' in h and 'Google Chrome' in h and 'Safari' in h and 'macOS' in h
        passes.append('Exercise 4: confirmation displays all validated selections')
        tok=re.search(r'name="csrf" value="([a-f0-9]+)"',h).group(1)
        _,redirect,h=request('/ex4-confirm.php',{'action':'save','csrf':tok})
        assert 'saved=1' in redirect and 'Information saved' in h
        passes.append('Exercise 4: Save action persists confirmation')
        assert 'linh@gmail.com' in (ROOT/'data/submissions.jsonl').read_text(encoding='utf-8')
        passes.append('Exercise 4: saved JSONL data on disk')
        _,_,h=request('/ex5.php')
        colors=re.findall(r'class="color-cell" style="background-color:(#[A-Fa-f0-9]{6})"',h)
        assert len(colors)==100 and len(set(colors))>=95
        passes.append('Exercise 5: 100 randomized PHP-generated color cells')
        _,_,h2=request('/ex5.php')
        colors2=re.findall(r'class="color-cell" style="background-color:(#[A-Fa-f0-9]{6})"',h2)
        assert colors!=colors2
        passes.append('Exercise 5: randomized colors on reload')
        status,_,css=request('/vendor/bootstrap.min.css')
        assert status==200 and 'Bootstrap v5.1.1' in css[:500]
        passes.append('Offline Bootstrap CSS served locally')
        status,_,js=request('/assets/color-picker.js')
        assert status==200 and 'selectedColor' in js
        passes.append('Exercise 5: interactive JS available')
    finally:
        proc.terminate()
        with contextlib.suppress(Exception):proc.wait(timeout=3)
        for path,contents in backed_up.items():
            if contents is None:path.unlink(missing_ok=True)
            else:path.write_bytes(contents)
    return passes

if __name__=='__main__':
    try:
        result=test()
    except Exception as e:
        print('FAIL:',str(e));raise
    for i,v in enumerate(result,1): print(f'PASS {i:02}: {v}')
    print(f'\nTOTAL: {len(result)}/{len(result)} tests passed.')
