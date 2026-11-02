<h1 align="center">🏢 HỆ THỐNG QUẢN LÝ TÒA CHUNG CƯ</h1>

<p align="center">
  <b>Đồ án môn học Phát triển Hệ thống Web – CT299-01</b><br>
  Trường Công nghệ Thông tin và Truyền thông – Đại học Cần Thơ<br>
  Học kỳ 1, năm học 2026 – 2027
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white">
  <img src="https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white">
  <img src="https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white">
  <img src="https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white">
  <img src="https://img.shields.io/badge/Bootstrap-7952B3?logo=bootstrap&logoColor=white">
  <img src="https://img.shields.io/badge/Trạng%20thái-Đang%20phát%20triển-yellow">
</p>

---

## 📑 Mục lục
- [Giới thiệu](#-giới-thiệu)
- [Thành viên nhóm](#-thành-viên-nhóm)
- [Chức năng chính](#-chức-năng-chính)
- [Công nghệ sử dụng](#️-công-nghệ-sử-dụng)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Tiến độ thực hiện](#-tiến-độ-thực-hiện)

---

## 📖 Giới thiệu
**Hệ thống quản lý tòa chung cư** là ứng dụng web hỗ trợ Ban quản lý chung cư quản lý thông tin căn hộ, cư dân, các khoản phí dịch vụ và tiếp nhận phản ánh của cư dân một cách tập trung, nhanh chóng và chính xác, thay thế cho cách quản lý thủ công bằng sổ sách hoặc Excel.

👩‍🏫 **Giảng viên hướng dẫn:** ThS. Nguyễn Thị Thanh Hiền

## 👥 Thành viên nhóm 6
| STT | MSSV | Họ và tên | Vai trò | GitHub |
|:---:|:----:|-----------|---------|--------|
| 1 | B2xxxxxx | Châu Gia Kiệt | ... | [@username](https://github.com/username) |
| 2 | B2xxxxxx | Nguyễn Minh Quý | ... | [@username](https://github.com/username) |
| 3 | B2xxxxxx | Trần Nhật Minh | ... | [@username](https://github.com/username) |
| 4 | B2xxxxxx | Hứa Nguyên Chí | ... | [@username](https://github.com/username) |
## ✨ Chức năng chính

### 🔑 Quản trị viên / Ban quản lý
- Đăng nhập, đăng xuất, phân quyền tài khoản
- Quản lý tòa nhà, tầng, căn hộ (thêm, sửa, xoá, tìm kiếm)
- Quản lý cư dân và chủ hộ
- Quản lý hợp đồng mua / thuê căn hộ
- Quản lý phí dịch vụ: phí quản lý, điện, nước, gửi xe
- Lập hoá đơn hằng tháng, theo dõi tình trạng thanh toán
- Đăng thông báo đến cư dân
- Tiếp nhận và xử lý phản ánh, yêu cầu sửa chữa
- Thống kê, báo cáo doanh thu và tình trạng căn hộ

### 🏠 Cư dân
- Xem thông tin căn hộ và thành viên hộ gia đình
- Xem hoá đơn, lịch sử thanh toán
- Xem thông báo từ Ban quản lý
- Gửi phản ánh, yêu cầu và theo dõi trạng thái xử lý
- Cập nhật thông tin cá nhân, đổi mật khẩu

## 🛠️ Công nghệ sử dụng
| Thành phần | Công nghệ |
|------------|-----------|
| Giao diện | HTML, CSS, JavaScript, Bootstrap |
| Xử lý phía máy chủ | PHP |
| Cơ sở dữ liệu | MySQL |
| Môi trường chạy | XAMPP (Apache + MySQL) |
| Quản lý mã nguồn | Git, GitHub |

## 📂 Cấu trúc thư mục
```
CT299-Nhom6/
├── assets/          # CSS, JS, hình ảnh
├── config/          # Kết nối cơ sở dữ liệu
├── admin/           # Trang dành cho Ban quản lý
├── resident/        # Trang dành cho cư dân
├── database/        # File .sql tạo cơ sở dữ liệu
├── index.php        # Trang chủ / đăng nhập
└── README.md
```

## 🚀 Hướng dẫn cài đặt
1. Clone repository:
   ```bash
   git clone https://github.com/huanguyenchi/CT299-Nhom6.git
   ```
2. Chép thư mục `CT299-Nhom6` vào `C:\xampp\htdocs\`.
3. Mở **XAMPP Control Panel**, bật **Apache** và **MySQL**.
4. Vào `http://localhost/phpmyadmin`, tạo cơ sở dữ liệu tên `quanlychungcu`, import file trong thư mục `database/`.
5. Mở trình duyệt truy cập: `http://localhost/CT299-Nhom6/`

**Tài khoản thử nghiệm:**
| Vai trò | Tên đăng nhập | Mật khẩu |
|---------|---------------|----------|
| Ban quản lý | admin | ... |
| Cư dân | cudan01 | ... |

## ✅ Tiến độ thực hiện
- [x] Phân tích yêu cầu, chọn đề tài
- [x] Tạo repository và mời thành viên
- [ ] Thiết kế cơ sở dữ liệu
- [ ] Thiết kế giao diện
- [ ] Chức năng quản lý căn hộ, cư dân
- [ ] Chức năng phí dịch vụ, hoá đơn
- [ ] Chức năng thông báo, phản ánh
- [ ] Kiểm thử và hoàn thiện báo cáo

---
<p align="center">© 2026 – Nhóm 6 – CT299-01 – Đại học Cần Thơ</p>
