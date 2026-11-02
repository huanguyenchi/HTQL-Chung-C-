<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\Tang;
use App\Models\Toa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TangController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $idToa = $request->input('id_toa');

        $tangs = Tang::with('toa')->withCount('phongs')
            ->when($idToa, fn ($query) => $query->where('id_toa', $idToa))
            ->when($q !== '', fn ($query) => $query->where('ten_tang', 'like', "%{$q}%"))
            ->orderBy('id_toa')->orderBy('ten_tang')
            ->paginate(10)->withQueryString();

        return view('quanly.tang.danh-sach', [
            'tangs' => $tangs, 'q' => $q, 'idToa' => $idToa,
            'toas' => Toa::orderBy('ten_toa')->get(),
        ]);
    }

    public function create(Request $request)
    {
        return view('quanly.tang.form', [
            'tang' => new Tang(['id_toa' => $request->input('id_toa')]),
            'toas' => Toa::orderBy('ten_toa')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $tang = Tang::create($this->validated($request));

        return redirect()->route('quanly.tang.index', ['id_toa' => $tang->id_toa])
            ->with('success', 'Đã thêm ' . $tang->ten_tang . '.');
    }

    public function edit(Tang $tang)
    {
        return view('quanly.tang.form', ['tang' => $tang, 'toas' => Toa::orderBy('ten_toa')->get()]);
    }

    public function update(Request $request, Tang $tang)
    {
        $tang->update($this->validated($request, $tang));

        return redirect()->route('quanly.tang.index', ['id_toa' => $tang->id_toa])
            ->with('success', 'Đã cập nhật ' . $tang->ten_tang . '.');
    }

    private function validated(Request $request, ?Tang $tang = null): array
    {
        return $request->validate([
            'id_toa' => 'required|exists:toa,id_toa',
            'ten_tang' => [
                'required', 'string', 'max:50',
                Rule::unique('tang', 'ten_tang')
                    ->where('id_toa', $request->input('id_toa'))
                    ->ignore($tang?->id_tang, 'id_tang'),
            ],
        ], [
            'ten_tang.unique' => 'Tòa này đã có tầng cùng tên.',
        ]);
    }
}
