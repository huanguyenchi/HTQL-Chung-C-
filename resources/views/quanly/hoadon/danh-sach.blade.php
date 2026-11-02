@extends('layouts.quanly')
@section('title', 'Quản lý hóa đơn')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý hóa đơn</h1><p>Tìm hóa đơn theo tháng, tòa, phòng và tình trạng thanh toán.</p></div>
        <a href="{{ route('quanly.diennuoc.create') }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Thêm hóa đơn</a>
    </div>
    <div class="card">
        <form class="filter-bar" method="GET">
            <input type="month" name="thang" value="{{ $thang }}" class="form-control" title="Tháng">
            <select name="id_toa" class="form-select">
                <option value="">Tất cả tòa</option>
                @foreach ($toas as $toa)
                    <option value="{{ $toa->id_toa }}" @selected($idToa == $toa->id_toa)>Tòa {{ $toa->ten_toa }}</option>
                @endforeach
            </select>
            <select name="tinhtrang" class="form-select">
                <option value="">Mọi tình trạng</option>
                <option value="0" @selected($tinhTrang === '0')>Chưa thanh toán</option>
                <option value="1" @selected($tinhTrang === '1')>Đã thanh toán</option>
            </select>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tên phòng...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($thang || $idToa || ($tinhTrang !== null && $tinhTrang !== '') || $q)<a href="{{ route('quanly.hoadon.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="px-3 py-2 small text-muted border-bottom">
            {{ $hoaDons->total() }} hóa đơn · tổng <strong class="text-dark">@vnd($tongTien)</strong>
        </div>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Mã</th><th>Tháng</th><th>Phòng</th><th class="text-end">Tiền phòng</th><th class="text-end">Tiền điện</th><th class="text-end">Tiền nước</th><th class="text-end">Thành tiền</th><th>Tình trạng</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($hoaDons as $hd)
                    <tr>
                        <td class="text-muted">#{{ $hd->id_hoadon }}</td>
                        <td>{{ $hd->thoigian->format('m/Y') }}</td>
                        <td class="fw-semibold">{{ $hd->phong->ten_phong }} <small class="text-muted fw-normal">· Tòa {{ $hd->phong->tang->toa->ten_toa }}</small></td>
                        <td class="text-end money">@vnd($hd->tienphong)</td>
                        <td class="text-end money">@vnd($hd->tiendien)</td>
                        <td class="text-end money">@vnd($hd->tiennuoc)</td>
                        <td class="text-end money">@vnd($hd->thanhtien)</td>
                        <td>
                            @if ($hd->daThanhToan())
                                <span class="chip chip-paid">Đã thanh toán</span>
                            @else
                                <span class="chip chip-unpaid">Chưa thanh toán</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('quanly.hoadon.show', $hd) }}" class="btn btn-sm btn-light-ocean">Xem</a>
                            @unless ($hd->daThanhToan())
                                <a href="{{ route('quanly.diennuoc.edit', $hd) }}" class="btn btn-sm btn-light-ocean" title="Cập nhật hóa đơn"><i class="bi bi-pencil"></i></a>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="empty"><i class="bi bi-receipt"></i>Không tìm thấy hóa đơn nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($hoaDons->hasPages())<div class="card-body border-top">{{ $hoaDons->links() }}</div>@endif
    </div>
@endsection
