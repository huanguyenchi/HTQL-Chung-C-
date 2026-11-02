@extends('layouts.quanly')
@section('title', $user->exists ? 'Sửa tài khoản' : 'Thêm tài khoản')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.taikhoan.index') }}">Quản lý tài khoản</a></li><li class="breadcrumb-item active">{{ $user->exists ? 'Sửa' : 'Thêm mới' }}</li></ol></nav>
            <h1>{{ $user->exists ? 'Sửa tài khoản ' . $user->ten : 'Thêm tài khoản mới' }}</h1>
        </div>
    </div>
    <div class="card" style="max-width:820px">
        <div class="card-body">
            <form method="POST" action="{{ $user->exists ? route('quanly.taikhoan.update', $user) : route('quanly.taikhoan.store') }}">
                @csrf
                @if ($user->exists) @method('PUT') @endif
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
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="sdt">Số điện thoại</label>
                        <input id="sdt" name="sdt" value="{{ old('sdt', $user->sdt) }}" class="form-control @error('sdt') is-invalid @enderror" placeholder="09xxxxxxxx">
                        @error('sdt')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="stk">Số tài khoản ngân hàng</label>
                        <input id="stk" name="stk" value="{{ old('stk', $user->stk) }}" class="form-control @error('stk') is-invalid @enderror" placeholder="Không bắt buộc">
                        @error('stk')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="quyen">Quyền</label>
                        <select id="quyen" name="quyen" class="form-select @error('quyen') is-invalid @enderror" @disabled($user->exists && $user->id_user == auth()->id())>
                            <option value="2" @selected(old('quyen', $user->quyen) == 2)>Cư dân</option>
                            <option value="1" @selected(old('quyen', $user->quyen) == 1)>Quản lý</option>
                        </select>
                        @if ($user->exists && $user->id_user == auth()->id())
                            <input type="hidden" name="quyen" value="1">
                            <div class="form-text">Bạn không thể tự đổi quyền của mình.</div>
                        @endif
                        @error('quyen')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="password">{{ $user->exists ? 'Mật khẩu mới' : 'Mật khẩu' }}</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $user->exists ? 'Để trống nếu không đổi' : 'Ít nhất 6 ký tự' }}">
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu</button>
                    <a href="{{ route('quanly.taikhoan.index') }}" class="btn btn-light-ocean">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
