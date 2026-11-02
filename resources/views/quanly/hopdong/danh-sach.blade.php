@extends('layouts.quanly')
@section('title', 'Quản lý hợp đồng')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý hợp đồng</h1><p>Hợp đồng thuê căn hộ giữa ban quản lý và cư dân.</p></div>
        <a href="{{ route('quanly.hopdong.create') }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Lập hợp đồng</a>
    </div>
    <div class="card">
        <form class="filter-bar" method="GET">
            <select name="loc" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả hợp đồng</option>
                <option value="hieuluc" @selected($loc === 'hieuluc')>Còn hiệu lực</option>
                <option value="saphethan" @selected($loc === 'saphethan')>Sắp hết hạn (30 ngày)</option>
                <option value="hethan" @selected($loc === 'hethan')>Đã hết hạn</option>
            </select>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tên cư dân hoặc phòng...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($q || $loc)<a href="{{ route('quanly.hopdong.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Mã</th><th>Cư dân</th><th>Phòng</th><th class="text-end">Giá / tháng</th><th>Thời hạn</th><th>Tình trạng</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($hopDongs as $hd)
                    <tr>
                        <td class="text-muted">#{{ $hd->id_hopdong }}</td>
                        <td class="fw-semibold">{{ $hd->cuDan->ten }}</td>
                        <td>{{ $hd->phong->ten_phong }} <small class="text-muted">· Tòa {{ $hd->phong->tang->toa->ten_toa }}</small></td>
                        <td class="text-end money">@vnd($hd->gia)</td>
                        <td class="text-nowrap">{{ $hd->ngaybatdau->format('d/m/Y') }} – {{ $hd->ngayketthuc->format('d/m/Y') }}</td>
                        <td>@include('hopdong._trangthai', ['hopDong' => $hd])</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('quanly.hopdong.show', $hd) }}" class="btn btn-sm btn-light-ocean">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="empty"><i class="bi bi-file-earmark-text"></i>Không tìm thấy hợp đồng nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($hopDongs->hasPages())<div class="card-body border-top">{{ $hopDongs->links() }}</div>@endif
    </div>
@endsection
