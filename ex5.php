<?php require_once __DIR__.'/includes/common.php'; lab_header('Bảng màu ngẫu nhiên',5); ?>
<main class="container pb-5">
<header class="page-head"><span class="lab-kicker">Exercise 5</span><h1>Bảng màu ngẫu nhiên</h1><p>PHP tạo 10 × 10 ô màu bằng vòng lặp <code>for</code> và <code>while</code>. Tải lại trang để nhận bộ màu mới.</p></header>
<div id="color-area" class="color-background"><div class="color-panel text-center">
 <div class="grid-board" id="color-grid" aria-label="Bảng chọn 100 màu">
 <?php for ($row=0; $row<10; $row++): ?>
 <?php $column=0; while ($column<10): $hex=sprintf('#%02X%02X%02X',random_int(0,255),random_int(0,255),random_int(0,255)); ?>
 <button type="button" class="color-cell" style="background-color:<?= esc($hex) ?>" data-color="<?= esc($hex) ?>" aria-label="Màu <?= esc($hex) ?>" title="<?= esc($hex) ?>"></button>
 <?php $column++; endwhile; endfor; ?>
 </div><p id="color-notice" class="color-notice font-weight-bold mt-3 mb-0" aria-live="polite"></p>
 </div></div><p class="note mt-3">Di chuột để xem thử màu nền. Nhấp vào ô để chọn màu cố định.</p>
</main>
<script src="assets/color-picker.js"></script>
<?php lab_footer(); ?>
