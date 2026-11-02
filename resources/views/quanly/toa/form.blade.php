@extends('layouts.quanly')
@section('title', $toa->exists ? 'Sửa tòa' : 'Thêm tòa')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.toa.index') }}">Quản lý tòa</a></li><li class="breadcrumb-item active">{{ $toa->exists ? 'Sửa' : 'Thêm mới' }}</li></ol></nav>
            <h1>{{ $toa->exists ? 'Sửa tòa ' . $toa->ten_toa : 'Thêm tòa mới' }}</h1>
        </div>
    </div>
    <div class="card" style="max-width:560px">
        <div class="card-body">
            <form method="POST" action="{{ $toa->exists ? route('quanly.toa.update', $toa) : route('quanly.toa.store') }}">
                @csrf
                @if ($toa->exists) @method('PUT') @endif
                <div class="mb-4">
                    <label class="form-label" for="ten_toa">Tên tòa</label>
                    <input id="ten_toa" name="ten_toa" value="{{ old('ten_toa', $toa->ten_toa) }}" class="form-control @error('ten_toa') is-invalid @enderror" placeholder="Ví dụ: A">
                    @error('ten_toa')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu</button>
                <a href="{{ route('quanly.toa.index') }}" class="btn btn-light-ocean">Hủy</a>
            </form>
        </div>
    </div>
@endsection
