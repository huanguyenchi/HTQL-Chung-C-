@extends('layouts.quanly')
@section('title', 'Hóa đơn #' . $hoaDon->id_hoadon)

@section('hoadon-ben-phai')
        <div class="card no-print">
            <div class="card-body d-grid gap-2">
                @if ($hoaDon->daThanhToan())
                    <div class="text-center text-muted small"><i class="bi bi-check-circle-fill text-success me-1"></i>Hóa đơn đã được xác nhận thanh toán.</div>
                @else
                    <form method="POST" action="{{ route('quanly.hoadon.thanhtoan', $hoaDon) }}" class="d-grid"
                          onsubmit="return confirm('Xác nhận hóa đơn #{{ $hoaDon->id_hoadon }} đã được thanh toán?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-cta py-2"><i class="bi bi-cash-coin me-1"></i>Xác nhận đã thanh toán</button>
                    </form>
                    <a href="{{ route('quanly.diennuoc.edit', $hoaDon) }}" class="btn btn-light-ocean"><i class="bi bi-pencil me-1"></i>Cập nhật hóa đơn</a>
                @endif
                @if ($hopDong)
                    <a href="{{ route('quanly.hopdong.show', $hopDong) }}" class="btn btn-light-ocean"><i class="bi bi-file-earmark-text me-1"></i>Xem hợp đồng</a>
                @endif
            </div>
        </div>
        @unless ($hoaDon->daThanhToan())
            <div class="mt-3">@include('hoadon._ma-qr')</div>
        @endunless
@endsection

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.hoadon.index') }}">Quản lý hóa đơn</a></li><li class="breadcrumb-item active">#{{ $hoaDon->id_hoadon }}</li></ol></nav>
            <h1>Chi tiết hóa đơn</h1>
        </div>
        <button onclick="window.print()" class="btn btn-light-ocean no-print"><i class="bi bi-printer me-1"></i>In hóa đơn</button>
    </div>

    @include('hoadon._chitiet')
@endsection
