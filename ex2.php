<?php
require_once __DIR__ . '/includes/common.php';
$values=['num1'=>'','num2'=>'','operation'=>'add'];
$ops=['add'=>['Cộng','+'],'subtract'=>['Trừ','-'],'multiply'=>['Nhân','×'],'divide'=>['Chia','÷']];
$error=''; $expression=''; $answer=null;
if ($_SERVER['REQUEST_METHOD']==='POST') {
    foreach ($values as $key=>$ignored) $values[$key]=trim((string)($_POST[$key]??''));
    if ($values['num1']==='' || $values['num2']==='') $error='Vui lòng nhập đầy đủ thông tin.';
    elseif (!is_numeric($values['num1']) || !is_numeric($values['num2']) || !is_finite((float)$values['num1']) || !is_finite((float)$values['num2'])) $error='Hai số hạng phải là số hợp lệ.';
    elseif (!isset($ops[$values['operation']])) $error='Vui lòng chọn phép tính hợp lệ.';
    else {
        $a=(float)$values['num1']; $b=(float)$values['num2'];
        if ($values['operation']==='divide' && $b==0.0) $error='Không thể chia cho 0.';
        else {
            $answer=match ($values['operation']) {'add'=>$a+$b,'subtract'=>$a-$b,'multiply'=>$a*$b,'divide'=>$a/$b};
            if (!is_finite($answer)) { $error='Kết quả vượt giới hạn tính toán.'; $answer=null; }
            else {
                $fmt=static fn(float $n):string => rtrim(rtrim(sprintf('%.10f',$n),'0'),'.');
                $expression=$fmt($a).' '.$ops[$values['operation']][1].' '.$fmt($b).' = '.$fmt($answer);
            }
        }
    }
}
lab_header('Máy tính cơ bản',2);
?>
<main class="container pb-5">
<header class="page-head"><span class="lab-kicker">Exercise 2</span><h1>Máy tính cơ bản</h1><p>Xử lý phép toán ở máy chủ bằng PHP và hiển thị kết quả với Bootstrap 4.</p></header>
<div class="lab-card calculator mx-auto">
 <h4 class="text-center mb-4">Tính toán cơ bản</h4>
 <form action="ex2.php" method="post" novalidate>
  <div class="form-group"><label for="num1">Số hạng 1</label><input class="form-control" type="text" inputmode="decimal" id="num1" name="num1" value="<?= esc($values['num1']) ?>" placeholder="Nhập số thứ nhất"></div>
  <div class="form-group"><label for="num2">Số hạng 2</label><input class="form-control" type="text" inputmode="decimal" id="num2" name="num2" value="<?= esc($values['num2']) ?>" placeholder="Nhập số thứ hai"></div>
  <fieldset class="form-group"><legend class="col-form-label">Phép tính</legend>
   <?php foreach ($ops as $key=>$labels): ?><div class="custom-control custom-radio custom-control-inline"><input class="custom-control-input" type="radio" name="operation" id="<?= esc($key) ?>" value="<?= esc($key) ?>" <?= $values['operation']===$key?'checked':'' ?>><label class="custom-control-label" for="<?= esc($key) ?>"><?= esc($labels[0]) ?></label></div><?php endforeach; ?>
  </fieldset>
  <?php if($error!==''): ?><div class="alert alert-danger" role="alert"><?= esc($error) ?></div><?php endif; ?>
  <button class="btn btn-success" type="submit">Xem kết quả</button>
 </form>
 <?php if ($answer!==null): ?><div class="alert alert-success mt-4 mb-0 text-center" role="status"><?= esc($expression) ?></div><?php endif; ?>
</div></main>
<?php lab_footer(); ?>
