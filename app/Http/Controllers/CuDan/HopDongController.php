<?php

namespace App\Http\Controllers\CuDan;

use App\Http\Controllers\Controller;
use App\Models\HopDong;

class HopDongController extends Controller
{
    public function index()
    {
        $hopDongs = HopDong::with('phong.tang.toa')
            ->where('id_user', auth()->id())
            ->orderByDesc('ngaybatdau')
            ->get();

        return view('cudan.hopdong.danh-sach', compact('hopDongs'));
    }

    public function show(HopDong $hopdong)
    {
        // Cư dân chỉ được xem hợp đồng của chính mình
        abort_unless((int) $hopdong->id_user === (int) auth()->id(), 403);

        $hopdong->load(['cuDan', 'quanLy', 'phong.tang.toa']);

        return view('cudan.hopdong.chi-tiet', ['hopDong' => $hopdong]);
    }
}
