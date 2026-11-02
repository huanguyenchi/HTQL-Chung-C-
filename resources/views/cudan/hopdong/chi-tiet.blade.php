@extends('layouts.cudan')
@section('title', 'Hợp đồng #' . $hopDong->id_hopdong)
@section('hero-title', 'Hợp đồng #' . $hopDong->id_hopdong)
@section('hero-sub', $hopDong->phong->ten_day_du)

@section('content')
    @include('hopdong._chitiet')
    <a href="{{ route('cudan.hopdong.index') }}" class="btn btn-light-ocean mt-3 no-print"><i class="bi bi-arrow-left me-1"></i>Danh sách hợp đồng</a>
@endsection
