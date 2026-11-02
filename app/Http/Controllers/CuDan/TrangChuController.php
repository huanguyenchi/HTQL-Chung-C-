<?php

namespace App\Http\Controllers\CuDan;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\HopDong;

class TrangChuController extends Controller
{
    public function index()
    {
        $id = (int) auth()->id();

        $hopDongs = HopDong::with('phong.tang.toa')->where('id_user', $id)->hieuLuc()->get();
        $hoaDonMoi = HoaDon::with('phong.tang.toa')->cuaCuDan($id)->orderByDesc('thoigian')->first();
        $tienNo = HoaDon::cuaCuDan($id)->where('tinhtrang', HoaDon::CHUA_THANH_TOAN)->sum('thanhtien');
        $soHoaDonNo = HoaDon::cuaCuDan($id)->where('tinhtrang', HoaDon::CHUA_THANH_TOAN)->count();

        return view('cudan.trang-chu', compact('hopDongs', 'hoaDonMoi', 'tienNo', 'soHoaDonNo'));
    }
}
