@extends('layouts.quanly')
@section('title', 'Quản lý tòa')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý tòa</h1><p>Danh sách các tòa nhà trong khu chung cư.</p></div>
        <a href="{{ route('quanly.toa.create') }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Thêm tòa</a>
    </div>
    <div class="card">
        <form class="filter-bar" method="GET">
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tìm theo tên tòa...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($q)<a href="{{ route('quanly.toa.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>#</th><th>Tên tòa</th><th>Số tầng</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($toas as $toa)
                    <tr>
                        <td class="text-muted">{{ $toas->firstItem() + $loop->index }}</td>
                        <td class="fw-semibold">Tòa {{ $toa->ten_toa }}</td>
                        <td><a href="{{ route('quanly.tang.index', ['id_toa' => $toa->id_toa]) }}">{{ $toa->tangs_count }} tầng</a></td>
                        <td class="text-end"><a href="{{ route('quanly.toa.edit', $toa) }}" class="btn btn-sm btn-light-ocean"><i class="bi bi-pencil me-1"></i>Sửa</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty"><i class="bi bi-buildings"></i>Không tìm thấy tòa nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($toas->hasPages())<div class="card-body border-top">{{ $toas->links() }}</div>@endif
    </div>
@endsection
