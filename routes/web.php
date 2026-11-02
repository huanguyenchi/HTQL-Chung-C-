<?php

use App\Http\Controllers\QuanLy;
use App\Http\Controllers\XacThucController;
use App\Http\Controllers\CuDan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('dangnhap');
    }

    return auth()->user()->isQuanLy()
        ? redirect()->route('quanly.tongquan')
        : redirect()->route('cudan.trangchu');
});

// ---------- Đăng nhập / đăng xuất ----------
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [XacThucController::class, 'hienDangNhap'])->name('dangnhap');
    Route::post('/dang-nhap', [XacThucController::class, 'dangNhap'])->name('dangnhap.post');
    Route::get('/dang-ky', [XacThucController::class, 'hienDangKy'])->name('dangky');
    Route::post('/dang-ky', [XacThucController::class, 'dangKy'])->name('dangky.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/dang-xuat', [XacThucController::class, 'dangXuat'])->name('dangxuat');
    Route::get('/doi-mat-khau', [XacThucController::class, 'hienDoiMatKhau'])->name('doimatkhau');
    Route::put('/doi-mat-khau', [XacThucController::class, 'doiMatKhau'])->name('doimatkhau.update');
});

// ---------- Quản lý (quyen = 1) ----------
Route::prefix('quan-ly')->name('quanly.')->middleware(['auth', 'quyen:1'])->group(function () {
    Route::get('/tong-quan', [QuanLy\TongQuanController::class, 'index'])->name('tongquan');

    Route::resource('toa', QuanLy\ToaController::class)->except(['show', 'destroy']);
    Route::resource('tang', QuanLy\TangController::class)->except(['show', 'destroy']);
    Route::resource('phong', QuanLy\PhongController::class)->except(['show', 'destroy']);

    Route::patch('taikhoan/{user}/khoa', [QuanLy\TaiKhoanController::class, 'khoa'])->name('taikhoan.khoa');
    Route::patch('taikhoan/{user}/duyet', [QuanLy\TaiKhoanController::class, 'duyet'])->name('taikhoan.duyet');
    Route::resource('taikhoan', QuanLy\TaiKhoanController::class)->except(['show', 'destroy'])->parameters(['taikhoan' => 'user']);

    Route::resource('hopdong', QuanLy\HopDongController::class)->only(['index', 'create', 'store', 'show']);

    // Quản lý điện nước: Thêm hóa đơn, Cập nhật hóa đơn
    Route::get('diennuoc/chiso-cu', [QuanLy\DienNuocController::class, 'chiSoCu'])->name('diennuoc.chisocu');
    Route::resource('diennuoc', QuanLy\DienNuocController::class)
        ->only(['create', 'store', 'edit', 'update'])
        ->parameters(['diennuoc' => 'hoadon']);

    // Quản lý hóa đơn: tìm kiếm, xem, xác nhận thanh toán
    Route::get('hoadon', [QuanLy\HoaDonController::class, 'index'])->name('hoadon.index');
    Route::get('hoadon/{hoadon}', [QuanLy\HoaDonController::class, 'show'])->name('hoadon.show');
    Route::patch('hoadon/{hoadon}/thanh-toan', [QuanLy\HoaDonController::class, 'thanhToan'])->name('hoadon.thanhtoan');
});

// ---------- Cư dân (quyen = 2) ----------
Route::prefix('cu-dan')->name('cudan.')->middleware(['auth', 'quyen:2'])->group(function () {
    Route::get('/trang-chu', [CuDan\TrangChuController::class, 'index'])->name('trangchu');
    Route::get('/thong-tin', [CuDan\ThongTinController::class, 'edit'])->name('thongtin');
    Route::put('/thong-tin', [CuDan\ThongTinController::class, 'update'])->name('thongtin.update');
    Route::get('/hopdong', [CuDan\HopDongController::class, 'index'])->name('hopdong.index');
    Route::get('/hopdong/{hopdong}', [CuDan\HopDongController::class, 'show'])->name('hopdong.show');
    Route::get('/hoadon', [CuDan\HoaDonController::class, 'index'])->name('hoadon.index');
    Route::get('/hoadon/{hoadon}', [CuDan\HoaDonController::class, 'show'])->name('hoadon.show');
});
