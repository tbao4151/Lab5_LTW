<?php require_once __DIR__ . '/includes/common.php'; lab_header('Trang chủ'); ?>
<main class="container pb-5">
 <header class="page-head">
  <span class="lab-kicker">Web Programming and Applications · Week 06</span>
  <h1 class="mt-2">Bài thực hành Lab 6</h1>
  <p>5 bài tập PHP dựa trên tài liệu Lab06.pdf; chọn bài để chạy và kiểm tra kết quả.</p>
 </header>
 <div class="row">
 <?php
 $lessons = [
     ['Bảng cửu chương','PHP + HTML + CSS hiển thị bảng nhân 1–10.'],
     ['Máy tính cơ bản','Cộng, trừ, nhân, chia bằng form Bootstrap và PHP.'],
     ['Bình luận lưu file','Lưu, đọc và xóa bình luận; sắp xếp mới nhất trước.'],
     ['Thông tin người dùng','Form thông tin và trang PHP xác nhận lựa chọn.'],
     ['Bảng màu ngẫu nhiên','Tạo 100 ô màu bằng vòng lặp for/while trong PHP.'],
 ];
 foreach ($lessons as $index => $lesson): ?>
 <div class="col-md-6 col-lg-4 mb-4"><a class="lab-link lab-card" href="ex<?= $index+1 ?>.php"><span class="lab-kicker">Exercise <?= $index+1 ?></span><h2 class="mt-2"><?= esc($lesson[0]) ?></h2><p class="mb-0"><?= esc($lesson[1]) ?></p></a></div>
 <?php endforeach; ?>
 </div>
 <p class="note">Cần chạy bằng máy chủ PHP (ví dụ XAMPP hoặc <code>php -S localhost:8000</code>), không mở file .php bằng nhấp đúp.</p>
</main>
<?php lab_footer(); ?>
