<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\Dien;
use App\Models\HoaDon;
use App\Models\HopDong;
use App\Models\Nuoc;
use App\Models\Phong;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DienNuocController extends Controller
{
    private const RULES_CHI_SO = [
        'dien_cu' => 'required|integer|min:0',
        'dien_moi' => 'required|integer|gte:dien_cu',
        'dien_dongia' => 'required|integer|min:1',
        'nuoc_cu' => 'required|integer|min:0',
        'nuoc_moi' => 'required|integer|gte:nuoc_cu',
        'nuoc_dongia' => 'required|integer|min:1',
        'khac' => 'nullable|string|max:100',
        'thuthem' => 'nullable|integer|min:0',
    ];

    private const MESSAGES = [
        'dien_moi.gte' => 'Chỉ số điện mới phải lớn hơn hoặc bằng chỉ số cũ.',
        'nuoc_moi.gte' => 'Chỉ số nước mới phải lớn hơn hoặc bằng chỉ số cũ.',
    ];

    public function create(Request $request)
    {
        return view('quanly.diennuoc.form', [
            'hoaDon' => null,
            'phongs' => $this->phongs(),
            'macDinh' => [
                'thang' => $request->input('thang', now()->subMonth()->format('Y-m')),
                'id_phong' => $request->input('id_phong'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_phong' => 'required|exists:phong,id_phong',
            'thang' => 'required|date_format:Y-m',
        ] + self::RULES_CHI_SO, self::MESSAGES);

        $thoiGian = Carbon::createFromFormat('!Y-m', $data['thang']);
        $ngay = $thoiGian->toDateString();

        $hopDong = $this->hopDongTrongThang((int) $data['id_phong'], $thoiGian);
        if (! $hopDong) {
            return back()->withErrors(['id_phong' => 'Phòng này không có hợp đồng thuê trong tháng đã chọn.'])->withInput();
        }

        if (HoaDon::where('id_phong', $data['id_phong'])->whereDate('thoigian', $ngay)->exists()) {
            return back()->withErrors(['thang' => 'Phòng này đã có hóa đơn tháng ' . $thoiGian->format('m/Y') . '. Hãy dùng chức năng cập nhật.'])->withInput();
        }

        $data['tienphong'] = (int) $hopDong->gia;

        $hoaDon = DB::transaction(function () use ($data, $ngay) {
            $dien = Dien::create([
                'id_phong' => $data['id_phong'], 'thoigian' => $ngay,
                'chiso_cu' => $data['dien_cu'], 'chiso_moi' => $data['dien_moi'], 'dongia' => $data['dien_dongia'],
            ]);
            $nuoc = Nuoc::create([
                'id_phong' => $data['id_phong'], 'thoigian' => $ngay,
                'chiso_cu' => $data['nuoc_cu'], 'chiso_moi' => $data['nuoc_moi'], 'dongia' => $data['nuoc_dongia'],
            ]);

            return HoaDon::create([
                'id_phong' => $data['id_phong'], 'id_dien' => $dien->id_dien, 'id_nuoc' => $nuoc->id_nuoc,
                'thoigian' => $ngay, 'tienphong' => $data['tienphong'], 'khac' => $data['khac'] ?? null, 'tinhtrang' => HoaDon::CHUA_THANH_TOAN,
            ] + $this->tinhTien($data));
        });

        return redirect()->route('quanly.hoadon.show', $hoaDon)
            ->with('success', 'Đã thêm hóa đơn điện nước, tổng tiền ' . number_format($hoaDon->thanhtien, 0, ',', '.') . ' đ.');
    }

    public function edit(HoaDon $hoadon)
    {
        $hoadon->load(['dien', 'nuoc', 'phong.tang.toa']);

        return view('quanly.diennuoc.form', [
            'hoaDon' => $hoadon,
            'phongs' => collect(),
            'macDinh' => [],
        ]);
    }

    public function update(Request $request, HoaDon $hoadon)
    {
        if ($hoadon->daThanhToan()) {
            return back()->with('error', 'Hóa đơn đã thanh toán, không thể cập nhật.');
        }

        $data = $request->validate(self::RULES_CHI_SO, self::MESSAGES);
        $data['tienphong'] = (int) $hoadon->tienphong;

        DB::transaction(function () use ($data, $hoadon) {
            $hoadon->dien->update([
                'chiso_cu' => $data['dien_cu'], 'chiso_moi' => $data['dien_moi'], 'dongia' => $data['dien_dongia'],
            ]);
            $hoadon->nuoc->update([
                'chiso_cu' => $data['nuoc_cu'], 'chiso_moi' => $data['nuoc_moi'], 'dongia' => $data['nuoc_dongia'],
            ]);
            $hoadon->update(['khac' => $data['khac'] ?? null] + $this->tinhTien($data));
        });

        return redirect()->route('quanly.hoadon.show', $hoadon)
            ->with('success', 'Đã cập nhật hóa đơn, tổng tiền mới ' . number_format($hoadon->thanhtien, 0, ',', '.') . ' đ.');
    }

    public function chiSoCu(Request $request)
    {
        $idPhong = (int) $request->input('id_phong');
        $thang = (string) $request->input('thang');

        if (! $idPhong || ! preg_match('/^\d{4}-\d{2}$/', $thang)) {
            return response()->json(['dien' => 0, 'nuoc' => 0, 'dien_dongia' => null, 'nuoc_dongia' => null, 'tienphong' => 0]);
        }

        $thoiGian = Carbon::createFromFormat('!Y-m', $thang);
        $ngay = $thoiGian->toDateString();
        $hopDong = $this->hopDongTrongThang($idPhong, $thoiGian);
        $dien = Dien::where('id_phong', $idPhong)->whereDate('thoigian', '<', $ngay)->orderByDesc('thoigian')->first();
        $nuoc = Nuoc::where('id_phong', $idPhong)->whereDate('thoigian', '<', $ngay)->orderByDesc('thoigian')->first();

        return response()->json([
            'dien' => $dien?->chiso_moi ?? 0,
            'nuoc' => $nuoc?->chiso_moi ?? 0,
            'dien_dongia' => $dien?->dongia,
            'nuoc_dongia' => $nuoc?->dongia,
            'tienphong' => (int) ($hopDong?->gia ?? 0),
            'cudan' => $hopDong?->cuDan?->ten,
        ]);
    }

    private function tinhTien(array $d): array
    {
        $tienDien = ((int) $d['dien_moi'] - (int) $d['dien_cu']) * (int) $d['dien_dongia'];
        $tienNuoc = ((int) $d['nuoc_moi'] - (int) $d['nuoc_cu']) * (int) $d['nuoc_dongia'];
        $thuThem = (int) ($d['thuthem'] ?? 0);
        $tienPhong = (int) ($d['tienphong'] ?? 0);

        return [
            'tiendien' => $tienDien,
            'tiennuoc' => $tienNuoc,
            'thuthem' => $thuThem,
            'thanhtien' => $tienPhong + $tienDien + $tienNuoc + $thuThem,
        ];
    }

    private function hopDongTrongThang(int $idPhong, Carbon $thoiGian): ?HopDong
    {
        return HopDong::with('cuDan')
            ->where('id_phong', $idPhong)
            ->whereDate('ngaybatdau', '<=', $thoiGian->copy()->endOfMonth()->toDateString())
            ->whereDate('ngayketthuc', '>=', $thoiGian->copy()->startOfMonth()->toDateString())
            ->orderByDesc('ngaybatdau')
            ->first();
    }

    private function phongs()
    {
        return Phong::with('tang.toa')
            ->whereHas('hopDongs')
            ->orderBy('ten_phong')->get();
    }
}
