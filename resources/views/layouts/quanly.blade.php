<!doctype html>
<html lang="vi">
<head>
    @include('chung.dau-trang')
</head>
<body>
@php
    $soChoDuyet = \App\Models\User::where('trangthai', \App\Models\User::CHO_DUYET)->count();
    $menu = [
        ['route' => 'quanly.tongquan', 'match' => 'quanly.tongquan', 'icon' => 'bi-grid-1x2', 'text' => 'Tổng quan'],
        ['section' => 'Tòa nhà'],
        ['route' => 'quanly.toa.index', 'match' => 'quanly.toa.*', 'icon' => 'bi-buildings', 'text' => 'Quản lý tòa'],
        ['route' => 'quanly.tang.index', 'match' => 'quanly.tang.*', 'icon' => 'bi-layers', 'text' => 'Quản lý tầng'],
        ['route' => 'quanly.phong.index', 'match' => 'quanly.phong.*', 'icon' => 'bi-door-open', 'text' => 'Quản lý phòng'],
        ['section' => 'Cư dân & hợp đồng'],
        ['route' => 'quanly.taikhoan.index', 'match' => 'quanly.taikhoan.*', 'icon' => 'bi-people', 'text' => 'Quản lý tài khoản'],
        ['route' => 'quanly.hopdong.index', 'match' => 'quanly.hopdong.*', 'icon' => 'bi-file-earmark-text', 'text' => 'Quản lý hợp đồng'],
        ['section' => 'Thu phí'],
        ['route' => 'quanly.diennuoc.create', 'match' => 'quanly.diennuoc.*', 'icon' => 'bi-lightning-charge', 'text' => 'Quản lý điện nước'],
        ['route' => 'quanly.hoadon.index', 'match' => 'quanly.hoadon.*', 'icon' => 'bi-receipt', 'text' => 'Quản lý hóa đơn'],
    ];
@endphp
<div class="app-shell">
    <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('quanly.tongquan') }}" class="brand">
                <span class="brand-icon"><i class="bi bi-water"></i></span>
                <span>Ocean Residence<small>Hệ thống quản lý chung cư</small></span>
            </a>
            <button type="button" class="btn-close btn-close-white me-3 d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Đóng"></button>
        </div>
        <nav class="pb-4">
            @foreach ($menu as $item)
                @if (isset($item['section']))
                    <div class="nav-section">{{ $item['section'] }}</div>
                @else
                    <a href="{{ route($item['route']) }}" class="side-link {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i> {{ $item['text'] }}
                        @if ($item['route'] === 'quanly.taikhoan.index' && $soChoDuyet > 0)
                            <span class="badge rounded-pill ms-auto" style="background:var(--ocean-gold);color:var(--ocean-navy)">{{ $soChoDuyet }}</span>
                        @endif
                    </a>
                    @if ($item['route'] === 'quanly.diennuoc.create' && request()->routeIs('quanly.diennuoc.*', 'quanly.hoadon.*'))
                        <a href="{{ route('quanly.diennuoc.create') }}" class="side-link sub {{ request()->routeIs('quanly.diennuoc.create') ? 'active' : '' }}">Thêm hóa đơn</a>
                        <a href="{{ route('quanly.hoadon.index', ['tinhtrang' => 0]) }}" class="side-link sub {{ request()->routeIs('quanly.diennuoc.edit') ? 'active' : '' }}">Cập nhật hóa đơn</a>
                    @endif
                @endif
            @endforeach
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="btn btn-light-ocean btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="Mở menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="ms-auto dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-reset" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar">{{ mb_substr(auth()->user()->ten, 0, 1) }}</span>
                    <span class="d-none d-sm-block lh-sm">
                        <span class="fw-semibold d-block">{{ auth()->user()->ten }}</span>
                        <small class="text-muted">Quản lý</small>
                    </span>
                    <i class="bi bi-chevron-down small text-muted"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li><a class="dropdown-item" href="{{ route('doimatkhau') }}"><i class="bi bi-key me-2"></i>Đổi mật khẩu</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('dangxuat') }}">@csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="content">
            @include('chung.thong-bao')
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
