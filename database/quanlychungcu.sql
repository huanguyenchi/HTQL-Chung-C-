-- =============================================================
--  CSDL: quanlychungcu  -  He thong quan ly toa chung cu
--  Import bang phpMyAdmin: chon tab Import -> chon file nay -> Go
--  Mat khau cua tat ca tai khoan mau: 123456
-- =============================================================

CREATE DATABASE IF NOT EXISTS quanlychungcu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quanlychungcu;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS hoadon, nuoc, dien, hopdong, phong, tang, toa, users;
SET FOREIGN_KEY_CHECKS = 1;

-- quyen: 1 = quan ly, 2 = cu dan
-- trangthai: 1 = hoat dong, 0 = bi khoa, 2 = cho duyet
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100) NOT NULL,
    ngaysinh DATE NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    sdt VARCHAR(15) NOT NULL,
    stk VARCHAR(30) NULL,
    ghichu_dangky TEXT NULL,
    password VARCHAR(255) NOT NULL,
    quyen TINYINT NOT NULL DEFAULT 2,
    trangthai TINYINT NOT NULL DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE toa (
    id_toa INT AUTO_INCREMENT PRIMARY KEY,
    ten_toa VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE tang (
    id_tang INT AUTO_INCREMENT PRIMARY KEY,
    id_toa INT NOT NULL,
    ten_tang VARCHAR(50) NOT NULL,
    UNIQUE (id_toa, ten_tang),
    FOREIGN KEY (id_toa) REFERENCES toa(id_toa)
) ENGINE=InnoDB;

-- id_user: cu dan dang o phong (NULL = phong trong), songuoi: so nguoi dang o
CREATE TABLE phong (
    id_phong INT AUTO_INCREMENT PRIMARY KEY,
    id_tang INT NOT NULL,
    ten_phong VARCHAR(50) NOT NULL,
    id_user INT NULL UNIQUE,
    songuoi TINYINT NOT NULL DEFAULT 0,
    UNIQUE (id_tang, ten_phong),
    FOREIGN KEY (id_tang) REFERENCES tang(id_tang),
    FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

CREATE TABLE hopdong (
    id_hopdong INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_ql INT NOT NULL,
    id_phong INT NOT NULL,
    noidung TEXT NULL,
    gia INT NOT NULL,
    ngaybatdau DATE NOT NULL,
    ngayketthuc DATE NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_ql) REFERENCES users(id_user),
    FOREIGN KEY (id_phong) REFERENCES phong(id_phong)
) ENGINE=InnoDB;

CREATE TABLE dien (
    id_dien INT AUTO_INCREMENT PRIMARY KEY,
    id_phong INT NOT NULL,
    thoigian DATE NOT NULL,
    chiso_cu INT NOT NULL,
    chiso_moi INT NOT NULL,
    dongia INT NOT NULL,
    FOREIGN KEY (id_phong) REFERENCES phong(id_phong)
) ENGINE=InnoDB;

CREATE TABLE nuoc (
    id_nuoc INT AUTO_INCREMENT PRIMARY KEY,
    id_phong INT NOT NULL,
    thoigian DATE NOT NULL,
    chiso_cu INT NOT NULL,
    chiso_moi INT NOT NULL,
    dongia INT NOT NULL,
    FOREIGN KEY (id_phong) REFERENCES phong(id_phong)
) ENGINE=InnoDB;

-- tinhtrang: 0 = chua thanh toan, 1 = da thanh toan
CREATE TABLE hoadon (
    id_hoadon INT AUTO_INCREMENT PRIMARY KEY,
    id_phong INT NOT NULL,
    id_dien INT NOT NULL,
    id_nuoc INT NOT NULL,
    thoigian DATE NOT NULL,
    tienphong INT NOT NULL DEFAULT 0,
    tiendien INT NOT NULL,
    tiennuoc INT NOT NULL,
    khac VARCHAR(100) NULL,
    thuthem INT NOT NULL DEFAULT 0,
    thanhtien INT NOT NULL,
    tinhtrang TINYINT NOT NULL DEFAULT 0,
    ngaythanhtoan DATETIME NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    UNIQUE (id_phong, thoigian),
    FOREIGN KEY (id_phong) REFERENCES phong(id_phong),
    FOREIGN KEY (id_dien) REFERENCES dien(id_dien),
    FOREIGN KEY (id_nuoc) REFERENCES nuoc(id_nuoc)
) ENGINE=InnoDB;

-- ---------------- Du lieu mau ----------------
INSERT INTO users (ten, ngaysinh, email, sdt, stk, ghichu_dangky, password, quyen, trangthai, created_at, updated_at) VALUES
('Nguyễn Văn Quản', '1990-05-12', 'admin@chungcu.test', '0901234567', '0123456789 (Vietcombank)', NULL, '$2y$10$uP3QOOHLwjPbR/eknT/DNOIJ01Sks6zBxzQ32vlx4j.ecztZLKDCi', 1, 1, NOW(), NOW()),
('Trần Thị Mai', '1998-03-20', 'mai@chungcu.test', '0912345678', NULL, NULL, '$2y$10$uP3QOOHLwjPbR/eknT/DNOIJ01Sks6zBxzQ32vlx4j.ecztZLKDCi', 2, 1, NOW(), NOW()),
('Lê Hoàng Nam', '1995-11-02', 'nam@chungcu.test', '0923456789', NULL, NULL, '$2y$10$uP3QOOHLwjPbR/eknT/DNOIJ01Sks6zBxzQ32vlx4j.ecztZLKDCi', 2, 1, NOW(), NOW()),
('Phạm Minh Thư', '2000-07-15', 'thu@chungcu.test', '0934567890', NULL, NULL, '$2y$10$uP3QOOHLwjPbR/eknT/DNOIJ01Sks6zBxzQ32vlx4j.ecztZLKDCi', 2, 1, NOW(), NOW()),
('Võ Thanh Tùng', '1999-09-09', 'tung@chungcu.test', '0945678901', NULL, 'Mình muốn thuê phòng B102 từ đầu tháng 11, đi 2 người.', '$2y$10$uP3QOOHLwjPbR/eknT/DNOIJ01Sks6zBxzQ32vlx4j.ecztZLKDCi', 2, 2, NOW(), NOW());

INSERT INTO toa (ten_toa) VALUES ('A'), ('B');

INSERT INTO tang (id_toa, ten_tang) VALUES
(1, 'Tầng 1'), (1, 'Tầng 2'), (1, 'Tầng 3'),
(2, 'Tầng 1'), (2, 'Tầng 2'), (2, 'Tầng 3');

INSERT INTO phong (id_tang, ten_phong) VALUES
(1, 'A101'),
(1, 'A102'),
(1, 'A103'),
(1, 'A104'),
(2, 'A201'),
(2, 'A202'),
(2, 'A203'),
(2, 'A204'),
(3, 'A301'),
(3, 'A302'),
(3, 'A303'),
(3, 'A304'),
(4, 'B101'),
(4, 'B102'),
(4, 'B103'),
(4, 'B104'),
(5, 'B201'),
(5, 'B202'),
(5, 'B203'),
(5, 'B204'),
(6, 'B301'),
(6, 'B302'),
(6, 'B303'),
(6, 'B304');

-- Cu dan dang o: Mai - A101, Nam - A102, Thu - B101
UPDATE phong SET id_user = 2, songuoi = 3 WHERE id_phong = 1;
UPDATE phong SET id_user = 3, songuoi = 2 WHERE id_phong = 2;
UPDATE phong SET id_user = 4, songuoi = 4 WHERE id_phong = 13;

INSERT INTO hopdong (id_user, id_ql, id_phong, noidung, gia, ngaybatdau, ngayketthuc, created_at, updated_at) VALUES
(2, 1, 1, 'Thuê căn hộ A101, đặt cọc 1 tháng, thanh toán trước ngày 5 hằng tháng.', 4500000, '2026-04-01', '2027-03-31', NOW(), NOW()),
(3, 1, 2, 'Thuê căn hộ A102, đặt cọc 1 tháng, thanh toán trước ngày 5 hằng tháng.', 5000000, '2026-04-01', '2026-11-01', NOW(), NOW()),
(4, 1, 13, 'Thuê căn hộ B101, đặt cọc 1 tháng, thanh toán trước ngày 5 hằng tháng.', 4000000, '2026-04-01', '2027-03-31', NOW(), NOW());

INSERT INTO dien (id_phong, thoigian, chiso_cu, chiso_moi, dongia) VALUES
(1, '2026-08-01', 100, 220, 3500),
(1, '2026-09-01', 220, 340, 3500),
(2, '2026-08-01', 140, 275, 3500),
(2, '2026-09-01', 275, 410, 3500),
(13, '2026-08-01', 180, 330, 3500),
(13, '2026-09-01', 330, 480, 3500);

INSERT INTO nuoc (id_phong, thoigian, chiso_cu, chiso_moi, dongia) VALUES
(1, '2026-08-01', 20, 28, 15000),
(1, '2026-09-01', 28, 36, 15000),
(2, '2026-08-01', 25, 34, 15000),
(2, '2026-09-01', 34, 43, 15000),
(13, '2026-08-01', 30, 40, 15000),
(13, '2026-09-01', 40, 50, 15000);

INSERT INTO hoadon (id_phong, id_dien, id_nuoc, thoigian, tienphong, tiendien, tiennuoc, khac, thuthem, thanhtien, tinhtrang, ngaythanhtoan, created_at, updated_at) VALUES
(1, 1, 1, '2026-08-01', 4500000, 420000, 120000, 'Phí vệ sinh', 50000, 5090000, 1, '2026-09-05 19:30:00', '2026-08-28 08:00:00', '2026-08-28 08:00:00'),
(1, 2, 2, '2026-09-01', 4500000, 420000, 120000, 'Phí vệ sinh', 50000, 5090000, 0, NULL, '2026-09-28 08:00:00', '2026-09-28 08:00:00'),
(2, 3, 3, '2026-08-01', 5000000, 472500, 135000, 'Phí vệ sinh', 50000, 5657500, 1, '2026-09-05 19:30:00', '2026-08-28 08:00:00', '2026-08-28 08:00:00'),
(2, 4, 4, '2026-09-01', 5000000, 472500, 135000, 'Phí vệ sinh', 50000, 5657500, 0, NULL, '2026-09-28 08:00:00', '2026-09-28 08:00:00'),
(13, 5, 5, '2026-08-01', 4000000, 525000, 150000, 'Phí vệ sinh', 50000, 4725000, 1, '2026-09-05 19:30:00', '2026-08-28 08:00:00', '2026-08-28 08:00:00'),
(13, 6, 6, '2026-09-01', 4000000, 525000, 150000, 'Phí vệ sinh', 50000, 4725000, 0, NULL, '2026-09-28 08:00:00', '2026-09-28 08:00:00');
