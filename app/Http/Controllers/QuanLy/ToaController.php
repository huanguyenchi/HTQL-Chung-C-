<?php

namespace App\Http\Controllers\QuanLy;

use App\Http\Controllers\Controller;
use App\Models\Toa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ToaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $toas = Toa::withCount('tangs')
            ->when($q !== '', fn ($query) => $query->where('ten_toa', 'like', "%{$q}%"))
            ->orderBy('ten_toa')
            ->paginate(10)->withQueryString();

        return view('quanly.toa.danh-sach', compact('toas', 'q'));
    }

    public function create()
    {
        return view('quanly.toa.form', ['toa' => new Toa()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ten_toa' => 'required|string|max:50|unique:toa,ten_toa',
        ]);

        Toa::create($data);

        return redirect()->route('quanly.toa.index')->with('success', 'Đã thêm tòa ' . $data['ten_toa'] . '.');
    }

    public function edit(Toa $toa)
    {
        return view('quanly.toa.form', compact('toa'));
    }

    public function update(Request $request, Toa $toa)
    {
        $data = $request->validate([
            'ten_toa' => ['required', 'string', 'max:50', Rule::unique('toa', 'ten_toa')->ignore($toa->id_toa, 'id_toa')],
        ]);

        $toa->update($data);

        return redirect()->route('quanly.toa.index')->with('success', 'Đã cập nhật tòa ' . $toa->ten_toa . '.');
    }
}
