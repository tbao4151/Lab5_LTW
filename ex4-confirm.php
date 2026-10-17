<?php
declare(strict_types=1);
require_once __DIR__.'/includes/common.php';
session_start();
$genders=['Male','Female']; $browsersAllowed=['Google Chrome','Firefox','Safari','Edge']; $oses=['Windows 10','macOS','Linux'];
$error='';$saved=isset($_GET['saved'])&&$_GET['saved']==='1';
if($_SERVER['REQUEST_METHOD']==='POST'&&($_POST['action']??'')==='save') {
  $token=(string)($_POST['csrf']??'');
  if(!isset($_SESSION['lab6_form_token'])||!hash_equals($_SESSION['lab6_form_token'],$token))$error='Phiên lưu không hợp lệ.';
  elseif(empty($_SESSION['lab6_form_data']))$error='Không có dữ liệu để lưu.';
  else{
    $line=json_encode(['saved_at'=>date(DATE_ATOM),'data'=>$_SESSION['lab6_form_data']],JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_SUBSTITUTE)."\n";
    if(file_put_contents(__DIR__.'/data/submissions.jsonl',$line,FILE_APPEND|LOCK_EX)===false)$error='Không lưu được dữ liệu, hãy kiểm tra quyền ghi thư mục data.';
    else {header('Location: ex4-confirm.php?saved=1',true,303);exit;}
  }
} elseif($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim((string)($_POST['name']??''));$email=trim((string)($_POST['email']??''));
  $gender=(string)($_POST['gender']??'');$os=(string)($_POST['os']??'');
  $browsers=$_POST['browsers']??[];
  if($name===''||strlen($name)>200)$error='Tên không được trống hoặc quá dài.';
  elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>150)$error='Email không hợp lệ.';
  elseif(!in_array($gender,$genders,true))$error='Giới tính không hợp lệ.';
  elseif(!in_array($os,$oses,true))$error='Hệ điều hành không hợp lệ.';
  elseif(!is_array($browsers)||count($browsers)>4||count(array_diff($browsers,$browsersAllowed))>0)$error='Trình duyệt không hợp lệ.';
  else {
    $_SESSION['lab6_form_data']=['name'=>$name,'email'=>$email,'gender'=>$gender,'browsers'=>array_values(array_unique($browsers)),'os'=>$os];
    $_SESSION['lab6_form_token']=bin2hex(random_bytes(20));
  }
}
$data=$_SESSION['lab6_form_data']??null;
lab_header('Confirm Information',4);
?>
<main class="container pb-5">
<header class="page-head"><span class="lab-kicker">Exercise 4</span><h1>Confirm Information</h1><p>Dữ liệu được nhận và xác nhận bởi trang PHP riêng.</p></header>
<div class="lab-card confirmation mx-auto"><h4 class="text-success text-center mb-4">Confirm Information</h4>
<?php if($error!==''):?><div class="alert alert-danger" role="alert"><?=esc($error)?></div><?php endif;?>
<?php if($saved):?><div class="alert alert-success" role="status">Information saved successfully.</div><?php endif;?>
<?php if($data!==null):?>
<dl><dt>Your name</dt><dd><?=esc($data['name'])?></dd><dt class="mt-3">Your email</dt><dd><?=esc($data['email'])?></dd><dt class="mt-3">Gender</dt><dd><?=esc($data['gender'])?></dd><dt class="mt-3">Favorite web browsers</dt><dd><?php if(!$data['browsers']):?>None selected<?php else:?><ul class="pl-4"><?php foreach($data['browsers'] as $b):?><li><?=esc($b)?></li><?php endforeach;?></ul><?php endif;?></dd><dt class="mt-3">Preferred Operating System</dt><dd><?=esc($data['os'])?></dd></dl>
<div class="d-flex gap-2 flex-wrap mt-4"><form action="ex4-confirm.php" method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="csrf" value="<?=esc($_SESSION['lab6_form_token'])?>"><button class="btn btn-success px-5" type="submit">Save</button></form><a class="btn btn-outline-success px-5" href="ex4.php">Back</a></div>
<?php else:?><p class="text-muted">Chưa có thông tin. Vui lòng nhập ở trang trước.</p><a class="btn btn-primary" href="ex4.php">Back to form</a><?php endif;?>
</div></main>
<?php lab_footer(); ?>
