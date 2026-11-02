@extends('layouts.cudan')
@section('title', 'Hợp đồng của tôi')
@section('hero-title', 'Hợp đồng của tôi')
@section('hero-sub', 'Các hợp đồng thuê căn hộ bạn đã ký với ban quản lý.')

@section('content')
    <div class="card">
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Mã</th><th>Căn hộ</th><th class="text-end">Giá / tháng</th><th>Thời hạn</th><th>Tình trạng</th><th></th></tr></thead>
                <tbody>
                @forelse ($hopDongs as $hd)
                    <tr>
                        <td class="text-muted">#{{ $hd->id_hopdong }}</td>
                        <td class="fw-semibold">{{ $hd->phong->ten_day_du }}</td>
                        <td class="text-end money">@vnd($hd->gia)</td>
                        <td class="text-nowrap">{{ $hd->ngaybatdau->format('d/m/Y') }} – {{ $hd->ngayketthuc->format('d/m/Y') }}</td>
                        <td>@include('hopdong._trangthai', ['hopDong' => $hd])</td>
                        <td class="text-end"><a href="{{ route('cudan.hopdong.show', $hd) }}" class="btn btn-sm btn-light-ocean">Xem</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty"><i class="bi bi-file-earmark-text"></i>Bạn chưa có hợp đồng nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
