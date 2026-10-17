# Lab 06 – Web Programming and Applications (503073)

Bài thực hành tuần 6 theo `Lab06.pdf`, Trường Đại học Tôn Đức Thắng.

## Chạy ứng dụng

**Yêu cầu:** PHP 8.0 trở lên (XAMPP bản có PHP 8+ hoặc PHP CLI). Không cần MySQL hoặc Node.js.

### Cách 1: PHP server

```bash
cd Lab06_LTW
php -S localhost:8000
```

Mở **http://localhost:8000/**. Tại đây có liên kết đến 5 bài.

### Cách 2: XAMPP

1. Chép thư mục `Lab06_LTW` vào `C:\xampp\htdocs\` (Windows).
2. Bật **Apache** trong XAMPP Control Panel.
3. Mở **http://localhost/Lab06_LTW/**.

> Không mở file `.php` trực tiếp bằng trình duyệt (`file://`), bởi PHP phải được máy chủ xử lý. Trong trường hợp muốn lưu bình luận hoặc lưu form bài 4, PHP cần quyền ghi vào thư mục `data/`.

## Các bài tập

| File | Nội dung |
|---|---|
| `ex1.php` | Bảng cửu chương 10×10 tạo bằng PHP, HTML và CSS. |
| `ex2.php` | Máy tính cộng, trừ, nhân, chia có xác thực đầu vào và kiểm tra chia 0. |
| `ex3.php` | Thêm/hiển thị/xóa bình luận, lưu bền trong `data/comments.jsonl` bằng PHP. Mới nhất ở trên. |
| `ex4.php` / `ex4-confirm.php` | Form thông tin với trang xác nhận riêng. Nút Save tùy chọn lưu vào `data/submissions.jsonl`. |
| `ex5.php` | Bảng 100 màu, dùng `for` và `while` trong PHP để sinh ngẫu nhiên 10 hàng × 10 cột. Có xem trước/giữ màu được chọn. |

## Tài nguyên

- `vendor/bootstrap.min.css`: Bootstrap v5.1.1 lưu cục bộ, dùng để hiển thị form (có CSS tương thích với các class được dùng trong template Bootstrap 4).
- `assets/style.css`: CSS bố cục và kiểu bảng.
- `assets/color-picker.js`: tương tác bổ sung cho bài 5; 100 ô màu vẫn sinh ở PHP.
- `tests/smoke_test.py`: script kiểm thử HTTP tích hợp PHP và chức năng.

## Kiểm thử

```bash
python tests/smoke_test.py
```

Script chạy server PHP ở cổng ngẫu nhiên và kiểm tra chức năng, không cần cài thư viện Python ngoài. Trường hợp không khởi động được PHP, hãy kiểm tra xem `php` đã có trong PATH chưa.

## Lưu ý

Hình minh họa Exercise 5 trong `Lab06.pdf` là bảng màu 10×10, dù mô tả văn bản nhắc “exercise 1 of week 5”. Ứng dụng triển khai theo **hình minh họa** và yêu cầu `for/while` của Lab 6.
