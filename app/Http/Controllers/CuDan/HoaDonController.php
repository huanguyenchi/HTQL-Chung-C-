<?php

namespace App\Http\Controllers\CuDan;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $thang = $request->input('thang');

        $hoaDons = HoaDon::with('phong.tang.toa')
            ->cuaCuDan((int) auth()->id())
            ->when($thang && preg_match('/^\d{4}-\d{2}$/', $thang), fn ($q) => $q->whereDate(
                'thoigian', Carbon::createFromFormat('!Y-m', $thang)->toDateString()
            ))
            ->orderByDesc('thoigian')
            ->paginate(10)->withQueryString();

        return view('cudan.hoadon.danh-sach', compact('hoaDons', 'thang'));
    }

    public function show(HoaDon $hoadon)
    {
        // Cư dân gõ tay ID hóa đơn của phòng khác -> 403
        abort_unless(HoaDon::cuaCuDan((int) auth()->id())->whereKey($hoadon->id_hoadon)->exists(), 403);

        $hoadon->load(['dien', 'nuoc', 'phong.tang.toa']);

        return view('cudan.hoadon.chi-tiet', [
            'hoaDon' => $hoadon,
            'hopDong' => $hoadon->hopDong(),
        ]);
    }
}
