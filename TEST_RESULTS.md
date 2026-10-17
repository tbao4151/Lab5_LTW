# Lab 06 – Báo cáo kiểm thử

**Ngày:** 17/10/2026

## Kiểm thử chức năng PHP thực tế

`python tests/smoke_test.py` khởi động PHP 8.4.24 bằng `php -S` trên localhost, gửi HTTP GET/POST thực tới từng trang, đọc kết quả và kiểm tra file lưu trữ.

**33/33 phép thử đạt.** Kiểm tra gồm:

- PHP lint 8 script, trang chủ và liên kết cả 5 bài.
- Bảng cửu chương có 100 ô, đúng kết quả 1 × 1 và 10 × 10.
- Máy tính: hiển thị, dữ liệu thiếu, phép nhân/trừ, chia 0, escape input.
- Bình luận: CSRF, ghi JSONL, thứ tự mới nhất, chặn XSS, xóa khỏi giao diện và ổ đĩa.
- Form xác nhận: chuyển sang trang PHP riêng, hiển thị trường/checkbox/OS, lưu file bằng Save.
- Bảng màu: 100 ô, sinh ngẫu nhiên mỗi lần tải, CSS Bootstrap và JS được phục vụ cục bộ.

Script khôi phục dữ liệu ở `data/` sau khi hoàn thành kiểm thử.

## Kiểm thử giao diện Chromium

Dùng `python tests/browser_test.py` để dựng nội dung HTML do PHP trả về trong Chromium ở kích thước **1280 × 850 (desktop)** và **390 × 850 (mobile)**. Nạp stylesheet và JavaScript trực tiếp vì môi trường trình duyệt không cho phép điều hướng tới localhost.

- Dựng thành công trang chủ và trang Exercise 1–5 tại cả hai kích thước.
- Không có lỗi JavaScript trong trình duyệt; thao tác chọn ô màu chạy đúng.
- Không thấy lỗi tràn trang ngang ở các trang, bảng cửu chương có vùng cuộn riêng trên màn hình hẹp.

**Giới hạn:** kiểm thử Chromium dựng từ HTML phản hồi, không phải điều hướng trực tiếp tới `http://localhost` và không kiểm thử form submit bằng Chromium. Submit và lưu file đã được kiểm thử bằng HTTP thực qua script backend.
