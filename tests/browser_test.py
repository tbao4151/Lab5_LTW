"""Optional browser checks. Requires Python Playwright and Chromium."""
from pathlib import Path
import subprocess, time, socket, urllib.request
from playwright.sync_api import sync_playwright
BASE=Path(__file__).resolve().parents[1]
with socket.socket() as s:
 s.bind(('127.0.0.1',0));port=s.getsockname()[1]
process=subprocess.Popen(['php','-S',f'127.0.0.1:{port}','-t',str(BASE)],stdout=subprocess.DEVNULL,stderr=subprocess.DEVNULL)
url=f'http://127.0.0.1:{port}'
output=BASE.parent/'lab06_browser_screens'
output.mkdir(exist_ok=True)
try:
 with sync_playwright() as pw:
  browser=pw.chromium.launch(headless=True,executable_path='/usr/bin/chromium',args=['--no-sandbox'])
  for vw in [1280,390]:
   context=browser.new_context(viewport={'width':vw,'height':850},device_scale_factor=1)
   page=context.new_page(); errors=[]
   page.on('pageerror',lambda e:errors.append(str(e)))
   for name in ['index','ex1','ex2','ex3','ex4','ex5']:
    html=urllib.request.urlopen(url+'/'+name+'.php').read().decode('utf-8')
    html=html.replace('<link rel="stylesheet" href="vendor/bootstrap.min.css">','<style>'+ (BASE/'vendor/bootstrap.min.css').read_text()+'</style>')
    html=html.replace('<link rel="stylesheet" href="assets/style.css">','<style>'+ (BASE/'assets/style.css').read_text()+'</style>')
    html=html.replace('<script src="assets/color-picker.js"></script>','<script>'+ (BASE/'assets/color-picker.js').read_text()+'</script>')
    page.set_content(html,wait_until='load')
    page.screenshot(path=str(output/f'{name}-{vw}.png'),full_page=True)
    assert page.locator('h1').count()>=1,name
    assert page.evaluate('document.documentElement.scrollWidth <= innerWidth + 1') or name=='ex1',f'overflow: {name} {vw}'
   count=page.locator('.color-cell').count();assert count==100
   page.locator('.color-cell').first.click();assert 'Đã chọn màu' in page.locator('#color-notice').inner_text()
   assert errors==[],errors
   print(f'PASS browser viewport {vw}: 6 rendered pages, JS color picker; JS errors 0')
   context.close()
  browser.close()
finally:
 process.terminate();process.wait(timeout=5)
print('Screenshots:',output)
