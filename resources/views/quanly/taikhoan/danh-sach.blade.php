@extends('layouts.quanly')
@section('title', 'Quản lý tài khoản')

@section('content')
    <div class="page-head">
        <div><h1>Quản lý tài khoản</h1><p>Tài khoản quản lý và cư dân; duyệt cư dân tự đăng ký, khóa tài khoản để chặn đăng nhập.</p></div>
        <a href="{{ route('quanly.taikhoan.create') }}" class="btn btn-cta"><i class="bi bi-person-plus me-1"></i>Thêm tài khoản</a>
    </div>
    @if ($soChoDuyet > 0 && (string) $trangThai !== '2')
        <div class="alert alert-info d-flex flex-wrap align-items-center gap-2">
            <i class="bi bi-person-exclamation"></i>
            <span>Có <strong>{{ $soChoDuyet }}</strong> tài khoản cư dân đang chờ duyệt.</span>
            <a href="{{ route('quanly.taikhoan.index', ['trangthai' => 2]) }}" class="btn btn-sm btn-cta ms-auto">Xem và duyệt</a>
        </div>
    @endif
    <div class="card">
        <form class="filter-bar" method="GET">
            <select name="quyen" class="form-select" onchange="this.form.submit()">
                <option value="">Tất cả quyền</option>
                <option value="1" @selected($quyen == 1)>Quản lý</option>
                <option value="2" @selected($quyen == 2)>Cư dân</option>
            </select>
            <select name="trangthai" class="form-select" onchange="this.form.submit()">
                <option value="">Mọi trạng thái</option>
                <option value="2" @selected((string) $trangThai === '2')>Chờ duyệt</option>
                <option value="1" @selected((string) $trangThai === '1')>Hoạt động</option>
                <option value="0" @selected((string) $trangThai === '0')>Bị khóa</option>
            </select>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Tên, email hoặc số điện thoại...">
            <button class="btn btn-primary"><i class="bi bi-search me-1"></i>Tìm</button>
            @if ($q || $quyen || ($trangThai !== null && $trangThai !== ''))<a href="{{ route('quanly.taikhoan.index') }}" class="btn btn-light-ocean">Bỏ lọc</a>@endif
        </form>
        <div class="table-wrap">
            <table class="table table-ocean table-hover">
                <thead><tr><th>Họ tên</th><th>Email</th><th>Điện thoại</th><th>Quyền</th><th>Trạng thái</th><th class="text-end">Thao tác</th></tr></thead>
                <tbody>
                @forelse ($users as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar">{{ mb_substr($u->ten, 0, 1) }}</span>
                                <div>
                                    <span class="fw-semibold">{{ $u->ten }}</span>
                                    @if ($u->choDuyet())
                                        <div class="small text-muted">Đăng ký {{ $u->created_at?->format('H:i d/m/Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            @if ($u->choDuyet() && $u->ghichu_dangky)
                                <div class="small mt-2 p-2 rounded-3" style="background:var(--ocean-mist);max-width:340px"><i class="bi bi-chat-left-text me-1"></i>{{ $u->ghichu_dangky }}</div>
                            @endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->sdt }}</td>
                        <td><span class="chip {{ $u->isQuanLy() ? 'chip-warn' : 'chip-active' }}">{{ $u->tenQuyen() }}</span></td>
                        <td>
                            @if ($u->choDuyet())
                                <span class="chip chip-warn">Chờ duyệt</span>
                            @elseif ($u->trangthai == 1)
                                <span class="chip chip-paid">Hoạt động</span>
                            @else
                                <span class="chip chip-locked">Bị khóa</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('quanly.taikhoan.edit', $u) }}" class="btn btn-sm btn-light-ocean"><i class="bi bi-pencil"></i></a>
                            @if ($u->choDuyet())
                                <form method="POST" action="{{ route('quanly.taikhoan.duyet', $u) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="ketqua" value="duyet">
                                    <button class="btn btn-sm btn-cta"><i class="bi bi-check2 me-1"></i>Duyệt</button>
                                </form>
                                <form method="POST" action="{{ route('quanly.taikhoan.duyet', $u) }}" class="d-inline"
                                      onsubmit="return confirm('Từ chối đăng ký của {{ $u->email }}?')">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="ketqua" value="tuchoi">
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg me-1"></i>Từ chối</button>
                                </form>
                            @elseif ($u->id_user != auth()->id())
                                <form method="POST" action="{{ route('quanly.taikhoan.khoa', $u) }}" class="d-inline"
                                      onsubmit="return confirm('{{ $u->trangthai == 1 ? 'Khóa' : 'Mở khóa' }} tài khoản {{ $u->email }}?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $u->trangthai == 1 ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                        <i class="bi {{ $u->trangthai == 1 ? 'bi-lock' : 'bi-unlock' }} me-1"></i>{{ $u->trangthai == 1 ? 'Khóa' : 'Mở khóa' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty"><i class="bi bi-people"></i>Không tìm thấy tài khoản nào.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())<div class="card-body border-top">{{ $users->links() }}</div>@endif
    </div>
@endsection
