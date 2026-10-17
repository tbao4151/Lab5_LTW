<?php require_once __DIR__.'/includes/common.php'; lab_header('Bảng cửu chương',1); ?>
<main class="container pb-5">
 <header class="page-head"><span class="lab-kicker">Exercise 1</span><h1>Bảng cửu chương</h1><p>Tạo bảng cửu chương 1–10 hoàn toàn bằng PHP, HTML và CSS.</p></header>
 <div class="mult-wrap"><table class="multiplication" aria-label="Bảng cửu chương từ một đến mười">
 <thead><tr><th colspan="10">BẢNG CỬU CHƯƠNG</th></tr></thead><tbody>
 <?php for ($multiplier=1; $multiplier<=10; $multiplier++): ?>
 <tr><?php for ($number=1; $number<=10; $number++): ?><td><?= $number ?> x <?= $multiplier ?> = <?= $number*$multiplier ?></td><?php endfor; ?></tr>
 <?php endfor; ?>
 </tbody></table></div>
</main>
<?php lab_footer(); ?>
