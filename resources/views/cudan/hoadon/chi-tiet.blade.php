@extends('layouts.cudan')
@section('title', 'Hóa đơn tháng ' . $hoaDon->thoigian->format('m/Y'))
@section('hero-title', 'Hóa đơn tháng ' . $hoaDon->thoigian->format('m/Y'))
@section('hero-sub', $hoaDon->phong->ten_day_du)

@section('hoadon-ben-phai')
    @unless ($hoaDon->daThanhToan())
        @include('hoadon._ma-qr')
    @endunless
@endsection

@section('content')
    @include('hoadon._chitiet')
    <a href="{{ route('cudan.hoadon.index') }}" class="btn btn-light-ocean mt-3 no-print"><i class="bi bi-arrow-left me-1"></i>Danh sách hóa đơn</a>
@endsection
