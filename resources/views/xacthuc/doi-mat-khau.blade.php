@extends(auth()->user()->isQuanLy() ? 'layouts.quanly' : 'layouts.cudan')
@section('title', 'Đổi mật khẩu')
@section('hero-title', 'Đổi mật khẩu')
@section('hero-sub', 'Mật khẩu mới cần ít nhất 6 ký tự.')

@section('content')
    @if (auth()->user()->isQuanLy())
        <div class="page-head"><div><h1>Đổi mật khẩu</h1><p>Mật khẩu mới cần ít nhất 6 ký tự.</p></div></div>
    @endif
    <div class="card" style="max-width:520px">
        <div class="card-body">
            <form method="POST" action="{{ route('doimatkhau.update') }}">
                @csrf @method('PUT')
                @foreach (['matkhau_cu' => 'Mật khẩu hiện tại', 'password' => 'Mật khẩu mới', 'password_confirmation' => 'Nhập lại mật khẩu mới'] as $name => $label)
                    <div class="mb-3">
                        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
                        <input type="password" id="{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror">
                        @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                @endforeach
                <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu mật khẩu</button>
            </form>
        </div>
    </div>
@endsection
