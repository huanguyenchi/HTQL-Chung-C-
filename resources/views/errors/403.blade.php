@section('title', 'Không có quyền')
<!doctype html>
<html lang="vi">
<head>
    @include('chung.dau-trang')
</head>
<body>
<div class="auth-art" style="min-height:100vh;justify-content:center;align-items:center;text-align:center">
    <span class="bubble" style="width:240px;height:240px;right:8%;top:10%"></span>
    <span class="bubble" style="width:110px;height:110px;left:12%;bottom:14%"></span>
    <div style="font-size:5rem;font-weight:800;line-height:1;color:var(--ocean-gold)">403</div>
    <h1 class="mx-auto mt-3" style="max-width:none;font-size:1.8rem">Bạn không có quyền truy cập trang này</h1>
    <p class="mx-auto">Chức năng này dành cho vai trò khác trong hệ thống.</p>
    <a href="{{ url('/') }}" class="btn btn-cta mt-3"><i class="bi bi-house me-1"></i>Về trang chủ</a>
</div>
</body>
</html>
