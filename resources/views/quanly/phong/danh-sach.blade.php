@extends('layouts.quanly')
@section('title', 'Quản lý phòng')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý phòng</h1><p>Phòng trống, phòng đang có người ở và cư dân ở từng phòng.</p></div>
        <a href="{{ route('quanly.phong.create', ['id_tang' => $idTang]) }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Thêm phòng</a>
    </div>
    <div class="card">
        <form class="filter-bar" method="GET">
            <select name="id_toa" class="form-select" onchange="this.form.id_tang.value='';this.form.submit()">
                <option value="">Tất cả tòa</option>
                @foreach ($toas as $toa)
                    <option value="{{ $toa->id_toa }}" @selected($idToa == $toa->id_toa)>Tòa {{ $toa->ten_toa }}</option>
                @endforeach
            </select>
            <select name="id_tang" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả tầng</option>
                @foreach ($toas as $toa)
                    @continue($idToa && $idToa != $toa->id_toa)
                    <optgroup label="Tòa {{ $toa->ten_toa }}">
                        @foreach ($toa->tangs as $tang)
                            <option value="{{ $tang->id_tang }}" @selected($idTang == $tang->id_tang)>{{ $tang->ten_tang }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tên phòng hoặc tên cư dân...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($q || $idToa || $idTang)<a href="{{ route('quanly.phong.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Phòng</th><th>Tầng</th><th>Tòa</th><th>Cư dân đang ở</th><th class="text-center">Số người ở</th><th>Tình trạng</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($phongs as $phong)
                    <tr>
                        <td class="fw-semibold">{{ $phong->ten_phong }}</td>
                        <td>{{ $phong->tang->ten_tang }}</td>
                        <td>Tòa {{ $phong->tang->toa->ten_toa }}</td>
                        <td>
                            @if ($phong->cuDan)
                                <div class="fw-semibold">{{ $phong->cuDan->ten }}</div>
                                <small class="text-muted">{{ $phong->cuDan->sdt }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $phong->songuoi ?: '—' }}</td>
                        <td>
                            @if ($phong->cuDan)
                                <span class="chip chip-active">Đang có người ở</span>
                            @else
                                <span class="chip chip-muted">Trống</span>
                            @endif
                        </td>
                        <td class="text-end"><a href="{{ route('quanly.phong.edit', $phong) }}" class="btn btn-sm btn-light-ocean"><i class="bi bi-pencil me-1"></i>Sửa</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty"><i class="bi bi-door-open"></i>Không tìm thấy phòng nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($phongs->hasPages())<div class="card-body border-top">{{ $phongs->links() }}</div>@endif
    </div>
@endsection
