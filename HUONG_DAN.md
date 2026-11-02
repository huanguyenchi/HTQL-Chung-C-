# Hệ thống quản lý tòa chung cư (Laravel 12 + MySQL)

## Chạy project
1. Mở XAMPP, Start **Apache** và **MySQL**.
2. Vào http://localhost/phpmyadmin, chọn tab **Import**, chọn file `database/quanlychungcu.sql`, bấm **Go**.
3. Mở http://localhost/quanlychungcu/

Tài khoản mẫu (mật khẩu `123456`): quản lý `admin@chungcu.test`; cư dân `mai@chungcu.test`, `nam@chungcu.test`, `thu@chungcu.test`; chờ duyệt `tung@chungcu.test`.

## Các thư mục chính
- `app/Models` – 8 model ứng với 8 bảng: User, Toa, Tang, Phong, HopDong, Dien, Nuoc, HoaDon
- `app/Http/Controllers/QuanLy` – chức năng của quản lý (TongQuan, Toa, Tang, Phong, TaiKhoan, HopDong, DienNuoc, HoaDon)
- `app/Http/Controllers/CuDan` – chức năng của cư dân
- `app/Http/Controllers/XacThucController.php` – đăng nhập, đăng ký, đổi mật khẩu
- `app/Http/Middleware/KiemTraQuyen.php` – phân quyền (quyen 1 = quản lý, 2 = cư dân)
- `routes/web.php` – khai báo đường dẫn
- `resources/views` – giao diện (Blade + Bootstrap 5): `quanly/`, `cudan/`, `xacthuc/`, `layouts/`, `chung/`
- `public/css/giaodien.css` – màu sắc giao diện
- `config/thanhtoan.php` – tài khoản ngân hàng để tạo mã QR
- `database/quanlychungcu.sql` – tạo CSDL và dữ liệu mẫu
