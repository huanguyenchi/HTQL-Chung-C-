@section('title', 'Đăng ký cư dân')
<!doctype html>
<html lang="vi">
<head>
    @include('chung.dau-trang')
</head>
<body>
<div class="auth-wrap">
    <section class="auth-art">
        <span class="bubble" style="width:260px;height:260px;right:-60px;top:-60px"></span>
        <span class="bubble" style="width:90px;height:90px;right:30%;top:38%"></span>
        <span class="bubble" style="width:150px;height:150px;left:-40px;bottom:12%"></span>
        <div class="brand p-0"><span class="brand-icon"><i class="bi bi-water"></i></span><span>Ocean Residence</span></div>
        <div>
            <h1>Đăng ký làm cư dân.</h1>
            <p class="mt-3">Điền thông tin của bạn, ban quản lý sẽ kiểm tra và duyệt tài khoản. Sau khi được duyệt, bạn đăng nhập để xem hợp đồng và thanh toán hóa đơn.</p>
        </div>
        <div class="glass">
            <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-list-check text-warning"></i><strong>Quy trình</strong></div>
            <ol class="small mb-0 ps-3" style="color:var(--ocean-mist)">
                <li>Gửi thông tin đăng ký</li>
                <li>Ban quản lý kiểm tra và duyệt</li>
                <li>Đăng nhập bằng email và mật khẩu đã tạo</li>
            </ol>
        </div>
    </section>

    <section class="auth-form">
        <div class="auth-card" style="max-width:520px">
            <h2 class="mb-1">Đăng ký tài khoản</h2>
            <p class="text-muted mb-4">Thông tin được gửi đến ban quản lý để duyệt.</p>

            <form method="POST" action="{{ route('dangky.post') }}" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-sm-7">
                        <label class="form-label" for="ten">Họ tên</label>
                        <input id="ten" name="ten" value="{{ old('ten') }}" class="form-control @error('ten') is-invalid @enderror" autofocus>
                        @error('ten')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label" for="ngaysinh">Ngày sinh</label>
                        <input type="date" id="ngaysinh" name="ngaysinh" value="{{ old('ngaysinh') }}" class="form-control @error('ngaysinh') is-invalid @enderror">
                        @error('ngaysinh')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-7">
                        <label class="form-label" for="email">Email (dùng để đăng nhập)</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label" for="sdt">Số điện thoại</label>
                        <input id="sdt" name="sdt" value="{{ old('sdt') }}" class="form-control @error('sdt') is-invalid @enderror" placeholder="09xxxxxxxx">
                        @error('sdt')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="stk">Số tài khoản ngân hàng <span class="text-muted fw-normal">(không bắt buộc)</span></label>
                        <input id="stk" name="stk" value="{{ old('stk') }}" class="form-control @error('stk') is-invalid @enderror">
                        @error('stk')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ít nhất 6 ký tự">
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" for="password_confirmation">Nhập lại mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="ghichu_dangky">Lời nhắn cho ban quản lý</label>
                        <textarea id="ghichu_dangky" name="ghichu_dangky" rows="3" class="form-control @error('ghichu_dangky') is-invalid @enderror" placeholder="Ví dụ: muốn thuê phòng B102 từ tháng 11, ở 2 người">{{ old('ghichu_dangky') }}</textarea>
                        @error('ghichu_dangky')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input @error('dongy') is-invalid @enderror" type="checkbox" name="dongy" id="dongy" value="1" @checked(old('dongy'))>
                            <label class="form-check-label" for="dongy">Tôi xác nhận thông tin trên là chính xác</label>
                        </div>
                        @error('dongy')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button class="btn btn-cta w-100 py-2 mt-4"><i class="bi bi-send me-1"></i> Gửi đăng ký</button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Đã có tài khoản? <a href="{{ route('dangnhap') }}" class="fw-semibold">Đăng nhập</a>
            </p>
        </div>
    </section>
</div>
</body>
</html>
