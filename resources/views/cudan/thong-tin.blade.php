@extends('layouts.cudan')
@section('title', 'Thông tin cá nhân')
@section('hero-title', 'Thông tin cá nhân')
@section('hero-sub', 'Cập nhật số điện thoại và tài khoản ngân hàng để ban quản lý liên hệ khi cần.')

@section('content')
    <div class="card" style="max-width:820px">
        <div class="card-body">
            <form method="POST" action="{{ route('cudan.thongtin.update') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="ten">Họ tên</label>
                        <input id="ten" name="ten" value="{{ old('ten', $user->ten) }}" class="form-control @error('ten') is-invalid @enderror">
                        @error('ten')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="ngaysinh">Ngày sinh</label>
                        <input type="date" id="ngaysinh" name="ngaysinh" value="{{ old('ngaysinh', $user->ngaysinh?->format('Y-m-d')) }}" class="form-control @error('ngaysinh') is-invalid @enderror">
                        @error('ngaysinh')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="email">Email đăng nhập</label>
                        <input id="email" value="{{ $user->email }}" class="form-control" readonly disabled>
                        <div class="form-text">Liên hệ quản lý nếu cần đổi email.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sdt">Số điện thoại</label>
                        <input id="sdt" name="sdt" value="{{ old('sdt', $user->sdt) }}" class="form-control @error('sdt') is-invalid @enderror">
                        @error('sdt')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="stk">Số tài khoản ngân hàng</label>
                        <input id="stk" name="stk" value="{{ old('stk', $user->stk) }}" class="form-control @error('stk') is-invalid @enderror">
                        @error('stk')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu thay đổi</button>
                    <a href="{{ route('doimatkhau') }}" class="btn btn-light-ocean"><i class="bi bi-key me-1"></i>Đổi mật khẩu</a>
                </div>
            </form>
        </div>
    </div>
@endsection
