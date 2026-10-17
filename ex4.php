<?php require_once __DIR__.'/includes/common.php'; lab_header('User Information',4); ?>
<main class="container pb-5">
<header class="page-head"><span class="lab-kicker">Exercise 4</span><h1>User Information</h1><p>Điền thông tin rồi gửi sang trang PHP khác để xác nhận lựa chọn.</p></header>
<div class="lab-card mx-auto" style="max-width:540px">
<h5 class="text-center mb-4">User Information</h5>
<form action="ex4-confirm.php" method="post">
<div class="form-group"><label for="name">Your name</label><input type="text" class="form-control" id="name" name="name" maxlength="100" placeholder="Your name" required></div>
<div class="form-group"><label for="email">Your email</label><input type="email" class="form-control" id="email" name="email" maxlength="150" placeholder="Your email" required></div>
<fieldset class="form-group"><legend class="col-form-label">Gender</legend>
<?php foreach(['Male','Female'] as $g):?><div class="custom-control custom-control-inline custom-radio"><input type="radio" class="custom-control-input" name="gender" id="gender-<?=esc($g)?>" value="<?=esc($g)?>" <?= $g==='Male'?'checked':''?>><label class="custom-control-label" for="gender-<?=esc($g)?>"><?=esc($g)?></label></div><?php endforeach;?>
</fieldset>
<fieldset class="form-group"><legend class="col-form-label">Favorite web browsers</legend>
<?php foreach(['Google Chrome','Firefox','Safari','Edge'] as $i=>$browser):?><div class="custom-control custom-checkbox"><input class="custom-control-input" type="checkbox" name="browsers[]" id="browser<?= $i ?>" value="<?=esc($browser)?>"><label class="custom-control-label" for="browser<?= $i ?>"><?=esc($browser)?></label></div><?php endforeach;?>
</fieldset>
<div class="form-group"><label for="os">Preferred Operating System</label><select class="custom-select" name="os" id="os"><option value="Windows 10">Windows 10</option><option value="macOS">macOS</option><option value="Linux">Linux</option></select></div>
<button type="submit" class="btn btn-primary px-5 mr-2">Send</button><button type="reset" class="btn btn-outline-primary px-5">Reset</button>
</form></div></main>
<?php lab_footer(); ?>
