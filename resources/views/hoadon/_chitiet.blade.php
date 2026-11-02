<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>Hóa đơn #{{ $hoaDon->id_hoadon }} · Tháng {{ $hoaDon->thoigian->format('m/Y') }}</span>
                @if ($hoaDon->daThanhToan())
                    <span class="chip chip-paid">Đã thanh toán</span>
                @else
                    <span class="chip chip-unpaid">Chưa thanh toán</span>
                @endif
            </div>
            <div class="card-body pb-2">
                <div class="row mb-3">
                    <div class="col-sm-6"><div class="text-muted small">Căn hộ</div><div class="fw-semibold">{{ $hoaDon->phong->ten_day_du }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Cư dân</div><div class="fw-semibold">{{ $hopDong?->cuDan->ten ?? '—' }}</div></div>
                </div>
            </div>
            <div class="table-wrap">
                <table class="table table-ocean">
                    <thead><tr><th>Khoản</th><th class="text-end">Chỉ số cũ</th><th class="text-end">Chỉ số mới</th><th class="text-end">Sử dụng</th><th class="text-end">Đơn giá</th><th class="text-end">Thành tiền</th></tr></thead>
                    <tbody>
                        <tr>
                            <td colspan="5"><i class="bi bi-house-door-fill me-1" style="color:var(--ocean-brand)"></i>Tiền thuê phòng tháng {{ $hoaDon->thoigian->format('m/Y') }}</td>
                            <td class="text-end money">@vnd($hoaDon->tienphong)</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Điện</td>
                            <td class="text-end">{{ $hoaDon->dien->chiso_cu }}</td>
                            <td class="text-end">{{ $hoaDon->dien->chiso_moi }}</td>
                            <td class="text-end">{{ $hoaDon->dien->soTieuThu() }} kWh</td>
                            <td class="text-end money">@vnd($hoaDon->dien->dongia)</td>
                            <td class="text-end money">@vnd($hoaDon->tiendien)</td>
                        </tr>
                        <tr>
                            <td><i class="bi bi-droplet-fill me-1" style="color:var(--ocean-cyan)"></i>Nước</td>
                            <td class="text-end">{{ $hoaDon->nuoc->chiso_cu }}</td>
                            <td class="text-end">{{ $hoaDon->nuoc->chiso_moi }}</td>
                            <td class="text-end">{{ $hoaDon->nuoc->soTieuThu() }} m³</td>
                            <td class="text-end money">@vnd($hoaDon->nuoc->dongia)</td>
                            <td class="text-end money">@vnd($hoaDon->tiennuoc)</td>
                        </tr>
                        <tr>
                            <td colspan="5"><i class="bi bi-plus-circle me-1 text-muted"></i>Thu thêm{{ $hoaDon->khac ? ': ' . $hoaDon->khac : '' }}</td>
                            <td class="text-end money">@vnd($hoaDon->thuthem)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="total-box mb-3">
            <div class="small" style="color:var(--ocean-mist)">Tổng thanh toán</div>
            <div class="money">@vnd($hoaDon->thanhtien)</div>
            <div class="small mt-1" style="color:var(--ocean-mist)">Lập ngày {{ $hoaDon->created_at?->format('d/m/Y') }}</div>
            @if ($hoaDon->daThanhToan() && $hoaDon->ngaythanhtoan)
                <div class="small mt-1" style="color:var(--ocean-gold)"><i class="bi bi-check-circle-fill me-1"></i>Đã thanh toán lúc {{ $hoaDon->ngaythanhtoan->format('H:i d/m/Y') }}</div>
            @endif
        </div>
        @yield('hoadon-ben-phai')
    </div>
</div>
