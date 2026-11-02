<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\Toa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $thang = $request->input('thang');
        $idToa = $request->input('id_toa');
        $tinhTrang = $request->input('tinhtrang');
        $q = trim((string) $request->input('q'));

        $query = HoaDon::with('phong.tang.toa')
            ->when($thang && preg_match('/^\d{4}-\d{2}$/', $thang), fn ($x) => $x->whereDate(
                'thoigian', Carbon::createFromFormat('!Y-m', $thang)->toDateString()
            ))
            ->when($idToa, fn ($x) => $x->whereHas('phong.tang', fn ($t) => $t->where('id_toa', $idToa)))
            ->when($tinhTrang !== null && $tinhTrang !== '', fn ($x) => $x->where('tinhtrang', (int) $tinhTrang))
            ->when($q !== '', fn ($x) => $x->whereHas('phong', fn ($p) => $p->where('ten_phong', 'like', "%{$q}%")));

        $tongTien = (clone $query)->sum('thanhtien');
        $hoaDons = $query->orderByDesc('thoigian')->orderBy('id_phong')->paginate(10)->withQueryString();

        return view('quanly.hoadon.danh-sach', [
            'hoaDons' => $hoaDons, 'tongTien' => $tongTien,
            'thang' => $thang, 'idToa' => $idToa, 'tinhTrang' => $tinhTrang, 'q' => $q,
            'toas' => Toa::orderBy('ten_toa')->get(),
        ]);
    }

    public function show(HoaDon $hoadon)
    {
        $hoadon->load(['dien', 'nuoc', 'phong.tang.toa']);

        return view('quanly.hoadon.chi-tiet', [
            'hoaDon' => $hoadon,
            'hopDong' => $hoadon->hopDong(),
        ]);
    }

    public function thanhToan(HoaDon $hoadon)
    {
        if ($hoadon->daThanhToan()) {
            return back()->with('error', 'Hóa đơn này đã được xác nhận thanh toán trước đó.');
        }

        $hoadon->update(['tinhtrang' => HoaDon::DA_THANH_TOAN, 'ngaythanhtoan' => now()]);

        return back()->with('success', 'Đã xác nhận thanh toán hóa đơn #' . $hoadon->id_hoadon . '.');
    }
}
