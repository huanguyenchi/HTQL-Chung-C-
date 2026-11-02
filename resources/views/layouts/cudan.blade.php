<!doctype html>
<html lang="vi">
<head>
    @include('chung.dau-trang')
</head>
<body>
@php
    $menu = [
        ['route' => 'cudan.trangchu', 'match' => 'cudan.trangchu', 'icon' => 'bi-house', 'text' => 'Trang chủ'],
        ['route' => 'cudan.hopdong.index', 'match' => 'cudan.hopdong.*', 'icon' => 'bi-file-earmark-text', 'text' => 'Hợp đồng'],
        ['route' => 'cudan.hoadon.index', 'match' => 'cudan.hoadon.*', 'icon' => 'bi-receipt', 'text' => 'Hóa đơn'],
        ['route' => 'cudan.thongtin', 'match' => 'cudan.thongtin*', 'icon' => 'bi-person', 'text' => 'Thông tin cá nhân'],
    ];
@endphp
<nav class="navbar navbar-expand-lg navbar-dark navbar-ocean py-2">
    <div class="container">
        <a href="{{ route('cudan.trangchu') }}" class="brand p-0 me-4">
            <span class="brand-icon"><i class="bi bi-water"></i></span>
            <span>Ocean Residence</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto gap-1 my-2 my-lg-0">
                @foreach ($menu as $item)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }} me-1"></i>{{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-white" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar">{{ mb_substr(auth()->user()->ten, 0, 1) }}</span>
                    <span class="fw-semibold">{{ auth()->user()->ten }}</span>
                    <i class="bi bi-chevron-down small"></i>
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
        </div>
    </div>
</nav>

<section class="resident-hero">
    <span class="bubble" style="width:180px;height:180px;right:6%;top:-40px"></span>
    <span class="bubble" style="width:70px;height:70px;right:22%;top:60px"></span>
    <div class="container">
        <h1>@yield('hero-title', 'Xin chào, ' . auth()->user()->ten)</h1>
        <p>@yield('hero-sub', 'Theo dõi hợp đồng và hóa đơn của bạn tại Ocean Residence.')</p>
    </div>
</section>

<main class="resident-body">
    <div class="container">
        @include('chung.thong-bao')
        @yield('content')
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
