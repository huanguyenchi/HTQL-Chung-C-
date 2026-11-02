@extends('layouts.cudan')
@section('title', 'Hóa đơn của tôi')
@section('hero-title', 'Hóa đơn của tôi')
@section('hero-sub', 'Hóa đơn điện nước của các phòng bạn đang thuê.')

@section('content')
    <div class="card">
        <form class="filter-bar" method="GET">
            <input type="month" name="thang" value="{{ $thang }}" class="form-control">
            <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i>Lọc</button>
            @if ($thang)<a href="{{ route('cudan.hoadon.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Tháng</th><th>Phòng</th><th class="text-end">Tiền phòng</th><th class="text-end">Điện</th><th class="text-end">Nước</th><th class="text-end">Tổng</th><th>Tình trạng</th><th></th></tr></thead>
                <tbody>
                @forelse ($hoaDons as $hd)
                    <tr>
                        <td class="fw-semibold">{{ $hd->thoigian->format('m/Y') }}</td>
                        <td>{{ $hd->phong->ten_phong }}</td>
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
                        <td class="text-end"><a href="{{ route('cudan.hoadon.show', $hd) }}" class="btn btn-sm btn-light-ocean">Xem</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="empty"><i class="bi bi-receipt"></i>Không có hóa đơn nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($hoaDons->hasPages())<div class="card-body border-top">{{ $hoaDons->links() }}</div>@endif
    </div>
@endsection
