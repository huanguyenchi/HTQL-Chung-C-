<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\HopDong;
use App\Models\Phong;
use App\Models\User;

class TongQuanController extends Controller
{
    public function index()
    {
        $dauThang = now()->startOfMonth()->toDateString();

        $tongPhong = Phong::count();
        $phongDaThue = Phong::whereNotNull('id_user')->count();
        $soNguoiO = Phong::sum('songuoi');
        $hoaDonChuaTT = HoaDon::where('tinhtrang', HoaDon::CHUA_THANH_TOAN)->count();
        $tienChuaThu = HoaDon::where('tinhtrang', HoaDon::CHUA_THANH_TOAN)->sum('thanhtien');
        $thuThangNay = HoaDon::where('tinhtrang', HoaDon::DA_THANH_TOAN)
            ->whereDate('ngaythanhtoan', '>=', $dauThang)->sum('thanhtien');

        $hoaDonMoi = HoaDon::with('phong.tang.toa')
            ->where('tinhtrang', HoaDon::CHUA_THANH_TOAN)
            ->orderByDesc('thoigian')->limit(6)->get();

        $sapHetHan = HopDong::with(['cuDan', 'phong.tang.toa'])
            ->hieuLuc()
            ->whereDate('ngayketthuc', '<=', now()->addDays(30)->toDateString())
            ->orderBy('ngayketthuc')->get();

        $choDuyet = User::where('trangthai', User::CHO_DUYET)->orderByDesc('created_at')->get();

        return view('quanly.tong-quan', compact(
            'choDuyet', 'tongPhong', 'phongDaThue', 'soNguoiO', 'hoaDonChuaTT', 'tienChuaThu', 'thuThangNay', 'hoaDonMoi', 'sapHetHan'
        ));
    }
}
