<?php
declare(strict_types=1);
require_once __DIR__.'/includes/common.php';
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
$path=__DIR__.'/data/comments.jsonl';
$allowed=['alert-secondary'=>'Gray','alert-success'=>'Green','alert-primary'=>'Blue','alert-danger'=>'Red','alert-warning'=>'Yellow'];
$_SESSION['lab6_comment_csrf']??=bin2hex(random_bytes(20));
$csrf=$_SESSION['lab6_comment_csrf'];
$error=''; $old=['name'=>'','comment'=>'','type'=>'alert-secondary'];
function read_comments(string $path):array {
    if(!is_file($path)) return [];
    $file=fopen($path,'rb');
    if($file===false) return [];
    flock($file,LOCK_SH);
    $body=stream_get_contents($file);
    flock($file,LOCK_UN);fclose($file);
    $items=[];
    foreach(explode("\n",(string)$body) as $line){
        if(trim($line)==='')continue;
        $entry=json_decode($line,true);
        if(is_array($entry)&&isset($entry['id'],$entry['name'],$entry['comment'],$entry['timestamp'],$entry['type']))$items[]=$entry;
    }
    return $items;
}
function remove_comment(string $path,string $id):bool {
    $file=fopen($path,'c+');if($file===false)return false;
    if(!flock($file,LOCK_EX)){fclose($file);return false;}
    rewind($file);$body=stream_get_contents($file);$kept=[];$found=false;
    foreach(explode("\n",(string)$body) as $line){
        if(trim($line)==='')continue;
        $entry=json_decode($line,true);
        if(is_array($entry) && ($entry['id']??null)===$id){$found=true;continue;}
        $kept[]=$line;
    }
    if($found){$updated=count($kept)?implode("\n",$kept)."\n":'';rewind($file);ftruncate($file,0);fwrite($file,$updated);fflush($file);}
    flock($file,LOCK_UN);fclose($file);return $found;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    $action=(string)($_POST['action']??'add');
    if(!hash_equals($csrf,(string)($_POST['csrf']??''))){$error='Phiên làm việc không hợp lệ, vui lòng tải lại trang.';}
    elseif($action==='delete'){
        $id=(string)($_POST['id']??'');
        if(preg_match('/\A[a-f0-9]{16}\z/',$id)&&remove_comment($path,$id)){
            header('Location: ex3.php?status=deleted',true,303);exit;
        }
        $error='Không tìm thấy bình luận để xóa.';
    }
    elseif($action==='add'){
        foreach($old as $key=>$default)$old[$key]=trim((string)($_POST[$key]??$default));
        if(!preg_match('/\A.{2,60}\z/us',$old['name']))$error='Họ và tên cần từ 2 đến 60 ký tự.';
        elseif($old['comment']==='' || strlen($old['comment'])>3000)$error='Nội dung bình luận không được trống hoặc dài quá 3000 byte.';
        elseif(!isset($allowed[$old['type']]))$error='Màu nền không hợp lệ.';
        else {
            $comment=['id'=>bin2hex(random_bytes(8)),'name'=>$old['name'],'type'=>$old['type'],'comment'=>$old['comment'],'timestamp'=>date('H:i - d/m/Y')];
            $line=json_encode($comment,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE)."\n";
            if(file_put_contents($path,$line,FILE_APPEND|LOCK_EX)===false)$error='Không ghi được bình luận vào thư mục data. Hãy kiểm tra quyền ghi file.';
            else{header('Location: ex3.php?status=added',true,303);exit;}
        }
    }else{$error='Thao tác không hợp lệ.';}
}
$comments=array_reverse(read_comments($path));
lab_header('Bình luận lưu file',3);
?>
<main class="container pb-5 comment-layout">
<header class="page-head"><span class="lab-kicker">Exercise 3</span><h1>Gửi và quản lý bình luận</h1><p>Bình luận được lưu vào file văn bản và vẫn còn khi tải lại trang. Bài mới hiển thị ở trên.</p></header>
<section class="lab-card">
<h4 class="text-center mb-4">Nhập bình luận của bạn</h4>
<?php if($error!==''):?><div role="alert" class="alert alert-danger"><?=esc($error)?></div><?php endif;?>
<?php if(($_GET['status']??'')==='added'):?><div role="status" class="alert alert-success">Đã lưu bình luận.</div><?php endif;?>
<?php if(($_GET['status']??'')==='deleted'):?><div role="status" class="alert alert-info">Đã xóa bình luận khỏi file.</div><?php endif;?>
<form method="post" action="ex3.php">
<input type="hidden" name="csrf" value="<?=esc($csrf)?>"><input type="hidden" name="action" value="add">
<div class="row"><div class="col-sm-6 form-group"><label for="name">Họ và tên</label><input class="form-control" id="name" name="name" maxlength="60" required value="<?=esc($old['name'])?>"></div><div class="col-sm-6 form-group"><label for="type">Chọn màu nền</label><select name="type" id="type" class="custom-select"><?php foreach($allowed as $key=>$label):?><option value="<?=esc($key)?>" <?= $old['type']===$key?'selected':''?>><?=esc($label)?></option><?php endforeach;?></select></div></div>
<div class="form-group"><label for="comment">Bình luận</label><textarea class="form-control" id="comment" name="comment" rows="3" required placeholder="Nhập nội dung"><?=esc($old['comment'])?></textarea></div>
<button type="submit" class="btn btn-primary">Gửi bình luận</button>
</form></section>
<section class="mt-3 comment-feed" aria-label="Danh sách bình luận">
<?php if(count($comments)===0):?><div class="lab-card text-center text-muted">Chưa có bình luận nào. Hãy gửi bình luận đầu tiên.</div><?php endif;?>
<?php foreach($comments as $c):$theme=isset($allowed[$c['type']])?$c['type']:'alert-secondary';?>
<div class="alert <?=esc($theme)?> comment-card mb-2"><strong><?=esc($c['name'])?>:</strong> <?=esc($c['comment'])?>
<div class="text-right text-end small mt-2"><?=esc($c['timestamp'])?></div>
<form method="post" action="ex3.php" class="close"><input type="hidden" name="csrf" value="<?=esc($csrf)?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=esc($c['id'])?>"><button type="submit" class="btn btn-sm btn-link text-dark p-0" aria-label="Xóa bình luận của <?=esc($c['name'])?>" title="Xóa bình luận">&times;</button></form>
</div>
<?php endforeach;?>
</section></main>
<?php lab_footer(); ?>
