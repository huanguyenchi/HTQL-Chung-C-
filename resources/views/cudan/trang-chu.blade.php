@extends('layouts.cudan')
@section('title', 'Trang chủ')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <div class="label">Phòng đang thuê</div>
                <div class="value">{{ $hopDongs->count() ? $hopDongs->pluck('phong.ten_phong')->join(', ') : '—' }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat gold">
                <span class="icon"><i class="bi bi-wallet2"></i></span>
                <div class="label">Số tiền còn nợ</div>
                <div class="value">@vnd($tienNo)</div>
                <small class="text-muted">{{ $soHoaDonNo }} hóa đơn chưa thanh toán</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat dark">
                <span class="icon"><i class="bi bi-receipt"></i></span>
                <div class="label">Hóa đơn gần nhất</div>
                <div class="value">{{ $hoaDonMoi ? 'Tháng ' . $hoaDonMoi->thoigian->format('m/Y') : '—' }}</div>
                @if ($hoaDonMoi)<small style="color:var(--ocean-mist)">@vnd($hoaDonMoi->thanhtien)</small>@endif
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header">Hợp đồng đang hiệu lực</div>
                <div class="card-body p-0">
                    @forelse ($hopDongs as $hd)
                        <a href="{{ route('cudan.hopdong.show', $hd) }}" class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom text-reset">
                            <div>
                                <div class="fw-semibold">{{ $hd->phong->ten_day_du }}</div>
                                <small class="text-muted">{{ $hd->ngaybatdau->format('d/m/Y') }} – {{ $hd->ngayketthuc->format('d/m/Y') }}</small>
                            </div>
                            <div class="text-end">
                                <div class="money">@vnd($hd->gia)</div>
                                @include('hopdong._trangthai', ['hopDong' => $hd])
                            </div>
                        </a>
                    @empty
                        <div class="empty"><i class="bi bi-file-earmark"></i>Bạn chưa có hợp đồng nào đang hiệu lực.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">Hóa đơn tháng gần nhất</div>
                <div class="card-body">
                    @if ($hoaDonMoi)
                        <div class="preview-box">
                            <div class="row-line"><span>Phòng</span><strong>{{ $hoaDonMoi->phong->ten_phong }}</strong></div>
                            <div class="row-line"><span>Tiền phòng</span><span class="money">@vnd($hoaDonMoi->tienphong)</span></div>
                            <div class="row-line"><span>Tiền điện</span><span class="money">@vnd($hoaDonMoi->tiendien)</span></div>
                            <div class="row-line"><span>Tiền nước</span><span class="money">@vnd($hoaDonMoi->tiennuoc)</span></div>
                            <div class="row-line"><span>Thu thêm</span><span class="money">@vnd($hoaDonMoi->thuthem)</span></div>
                            <div class="row-line grand"><span>Tổng</span><span class="money">@vnd($hoaDonMoi->thanhtien)</span></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if ($hoaDonMoi->daThanhToan())
                                <span class="chip chip-paid">Đã thanh toán</span>
                            @else
                                <span class="chip chip-unpaid">Chưa thanh toán</span>
                            @endif
                            <a href="{{ route('cudan.hoadon.show', $hoaDonMoi) }}" class="btn btn-cta btn-sm">
                                @if ($hoaDonMoi->daThanhToan()) Xem chi tiết @else <i class="bi bi-qr-code me-1"></i>Thanh toán bằng QR @endif
                            </a>
                        </div>
                    @else
                        <div class="empty"><i class="bi bi-receipt"></i>Chưa có hóa đơn nào.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
