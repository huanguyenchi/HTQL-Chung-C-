@section('title', 'Đăng nhập')
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
            <h1>Quản lý chung cư nhẹ như sóng.</h1>
            <p class="mt-3">Tòa, tầng, phòng, hợp đồng, điện nước và hóa đơn — tất cả ở một nơi cho quản lý và cư dân.</p>
        </div>
        <div class="glass">
            <div class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-shield-check text-warning"></i><strong>Phân quyền rõ ràng</strong></div>
            <small class="text-white-50">Quản lý thao tác toàn bộ dữ liệu, cư dân chỉ xem hợp đồng và hóa đơn của mình.</small>
        </div>
    </section>

    <section class="auth-form">
        <div class="auth-card">
            <h2 class="mb-1">Đăng nhập</h2>
            <p class="text-muted mb-4">Dùng email và mật khẩu được ban quản lý cấp.</p>

            @include('chung.thong-bao')

            <form method="POST" action="{{ route('dangnhap.post') }}" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" autofocus
                           class="form-control @error('email') is-invalid @enderror" placeholder="ban@chungcu.test">
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" placeholder="••••••">
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-cta w-100 py-2"><i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập</button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Chưa có tài khoản? <a href="{{ route('dangky') }}" class="fw-semibold">Đăng ký cư dân</a>
            </p>
        </div>
    </section>
</div>
</body>
</html>
