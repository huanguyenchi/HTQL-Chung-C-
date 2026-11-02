@extends('layouts.quanly')
@section('title', $tang->exists ? 'Sửa tầng' : 'Thêm tầng')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.tang.index') }}">Quản lý tầng</a></li><li class="breadcrumb-item active">{{ $tang->exists ? 'Sửa' : 'Thêm mới' }}</li></ol></nav>
            <h1>{{ $tang->exists ? 'Sửa ' . $tang->ten_tang : 'Thêm tầng mới' }}</h1>
        </div>
    </div>
    <div class="card" style="max-width:560px">
        <div class="card-body">
            <form method="POST" action="{{ $tang->exists ? route('quanly.tang.update', $tang) : route('quanly.tang.store') }}">
                @csrf
                @if ($tang->exists) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label" for="id_toa">Thuộc tòa</label>
                    <select id="id_toa" name="id_toa" class="form-select @error('id_toa') is-invalid @enderror">
                        <option value="">-- Chọn tòa --</option>
                        @foreach ($toas as $toa)
                            <option value="{{ $toa->id_toa }}" @selected(old('id_toa', $tang->id_toa) == $toa->id_toa)>Tòa {{ $toa->ten_toa }}</option>
                        @endforeach
                    </select>
                    @error('id_toa')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="ten_tang">Tên tầng</label>
                    <input id="ten_tang" name="ten_tang" value="{{ old('ten_tang', $tang->ten_tang) }}" class="form-control @error('ten_tang') is-invalid @enderror" placeholder="Ví dụ: Tầng 1">
                    @error('ten_tang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu</button>
                <a href="{{ route('quanly.tang.index') }}" class="btn btn-light-ocean">Hủy</a>
            </form>
        </div>
    </div>
@endsection
