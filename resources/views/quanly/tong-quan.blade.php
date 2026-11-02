@extends('layouts.quanly')
@section('title', 'Tổng quan')

@section('content')
    <div class="page-head">
        <div>
            <h1>Tổng quan</h1>
            <p>Tình hình tòa nhà ngày {{ now()->format('d/m/Y') }}.</p>
        </div>
        <a href="{{ route('quanly.diennuoc.create') }}" class="btn btn-cta"><i class="bi bi-plus-lg me-1"></i>Thêm hóa đơn điện nước</a>
    </div>

    @if ($choDuyet->count())
        <div class="card mb-4" style="border-left:4px solid var(--ocean-gold)">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">
                <i class="bi bi-person-plus fs-4" style="color:#c97d00"></i>
                <div class="flex-grow-1">
                    <div class="fw-bold" style="color:var(--ocean-navy)">{{ $choDuyet->count() }} tài khoản cư dân đang chờ duyệt</div>
                    <div class="small text-muted">{{ $choDuyet->pluck('ten')->take(3)->join(', ') }}{{ $choDuyet->count() > 3 ? '…' : '' }}</div>
                </div>
                <a href="{{ route('quanly.taikhoan.index', ['trangthai' => 2]) }}" class="btn btn-cta">Duyệt ngay</a>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat dark">
                <span class="icon"><i class="bi bi-door-open"></i></span>
                <div class="label">Tổng số phòng</div>
                <div class="value">{{ $tongPhong }}</div>
                <div class="small opacity-75">{{ $soNguoiO }} người đang ở</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat">
                <span class="icon"><i class="bi bi-house-check"></i></span>
                <div class="label">Phòng đang cho thuê</div>
                <div class="value">{{ $phongDaThue }}<span class="fs-6 text-muted fw-semibold"> / {{ $tongPhong }}</span></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat gold">
                <span class="icon"><i class="bi bi-hourglass-split"></i></span>
                <div class="label">Hóa đơn chưa thanh toán</div>
                <div class="value">{{ $hoaDonChuaTT }}</div>
                <small class="text-muted">@vnd($tienChuaThu)</small>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat">
                <span class="icon"><i class="bi bi-cash-stack"></i></span>
                <div class="label">Đã thu trong tháng {{ now()->format('m/Y') }}</div>
                <div class="value fs-4">@vnd($thuThangNay)</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Hóa đơn chưa thanh toán
                    <a href="{{ route('quanly.hoadon.index', ['tinhtrang' => 0]) }}" class="small fw-semibold">Xem tất cả</a>
                </div>
                <div class="table-wrap">
                    <table class="table table-ocean table-hover">
                        <thead><tr><th>Phòng</th><th>Tháng</th><th class="text-end">Thành tiền</th><th></th></tr></thead>
                        <tbody>
                        @forelse ($hoaDonMoi as $hd)
                            <tr>
                                <td><strong>{{ $hd->phong->ten_phong }}</strong><div class="small text-muted">Tòa {{ $hd->phong->tang->toa->ten_toa }}</div></td>
                                <td>{{ $hd->thoigian->format('m/Y') }}</td>
                                <td class="text-end money">@vnd($hd->thanhtien)</td>
                                <td class="text-end"><a href="{{ route('quanly.hoadon.show', $hd) }}" class="btn btn-sm btn-light-ocean">Xem</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty"><i class="bi bi-emoji-smile"></i>Không có hóa đơn nào đang nợ.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-header">Hợp đồng sắp hết hạn (30 ngày)</div>
                <div class="card-body p-0">
                    @forelse ($sapHetHan as $hd)
                        <a href="{{ route('quanly.hopdong.show', $hd) }}" class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom text-reset">
                            <div>
                                <div class="fw-semibold">{{ $hd->cuDan->ten }}</div>
                                <small class="text-muted">{{ $hd->phong->ten_day_du }}</small>
                            </div>
                            <span class="chip chip-warn">{{ $hd->ngayketthuc->format('d/m/Y') }}</span>
                        </a>
                    @empty
                        <div class="empty"><i class="bi bi-calendar-check"></i>Chưa có hợp đồng nào sắp hết hạn.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
