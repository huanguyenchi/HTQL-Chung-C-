@extends('layouts.quanly')
@section('title', 'Quản lý tầng')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý tầng</h1><p>Các tầng thuộc từng tòa.</p></div>
        <a href="{{ route('quanly.tang.create', ['id_toa' => $idToa]) }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Thêm tầng</a>
    </div>
    <div class="card">
        <form class="filter-bar" method="GET">
            <select name="id_toa" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả tòa</option>
                @foreach ($toas as $toa)
                    <option value="{{ $toa->id_toa }}" @selected($idToa == $toa->id_toa)>Tòa {{ $toa->ten_toa }}</option>
                @endforeach
            </select>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tên tầng...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($q || $idToa)<a href="{{ route('quanly.tang.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>#</th><th>Tầng</th><th>Tòa</th><th>Số phòng</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($tangs as $tang)
                    <tr>
                        <td class="text-muted">{{ $tangs->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">{{ $tang->ten_tang }}</td>
                        <td>Tòa {{ $tang->toa->ten_toa }}</td>
                        <td><a href="{{ route('quanly.phong.index', ['id_tang' => $tang->id_tang]) }}">{{ $tang->phongs_count }} phòng</a></td>
                        <td class="text-end"><a href="{{ route('quanly.tang.edit', $tang) }}" class="btn btn-sm btn-light-ocean"><i class="bi bi-pencil me-1"></i>Sửa</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty"><i class="bi bi-layers"></i>Không tìm thấy tầng nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($tangs->hasPages())<div class="card-body border-top">{{ $tangs->links() }}</div>@endif
    </div>
@endsection
