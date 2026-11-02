@extends('layouts.quanly')
@section('title', 'Hợp đồng #' . $hopDong->id_hopdong)

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.hopdong.index') }}">Quản lý hợp đồng</a></li><li class="breadcrumb-item active">#{{ $hopDong->id_hopdong }}</li></ol></nav>
            <h1>Chi tiết hợp đồng</h1>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-light-ocean"><i class="bi bi-printer me-1"></i>In</button>
        </div>
    </div>
    @include('hopdong._chitiet')
@endsection
