<div class="row g-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                Hợp đồng thuê căn hộ #{{ $hopDong->id_hopdong }}
                @include('hopdong._trangthai', ['hopDong' => $hopDong])
            </div>
            <div class="card-body">
                <dl class="row dl-ocean mb-0">
                    <dt class="col-sm-4">Căn hộ</dt><dd class="col-sm-8">{{ $hopDong->phong->ten_day_du }}</dd>
                    <dt class="col-sm-4">Bên thuê (cư dân)</dt><dd class="col-sm-8">{{ $hopDong->cuDan->ten }} · {{ $hopDong->cuDan->sdt }}</dd>
                    <dt class="col-sm-4">Bên cho thuê (quản lý)</dt><dd class="col-sm-8">{{ $hopDong->quanLy->ten }} · {{ $hopDong->quanLy->sdt }}</dd>
                    <dt class="col-sm-4">Thời hạn</dt><dd class="col-sm-8">{{ $hopDong->ngaybatdau->format('d/m/Y') }} – {{ $hopDong->ngayketthuc->format('d/m/Y') }}</dd>
                    <dt class="col-sm-4">Nội dung</dt><dd class="col-sm-8 fw-normal" style="white-space:pre-line">{{ $hopDong->noidung ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="total-box h-100 d-flex flex-column justify-content-center">
            <div class="small" style="color:var(--ocean-mist)">Giá thuê mỗi tháng</div>
            <div class="money">@vnd($hopDong->gia)</div>
            <div class="small mt-2" style="color:var(--ocean-mist)">
                Còn {{ max(0, (int) now()->startOfDay()->diffInDays($hopDong->ngayketthuc, false)) }} ngày đến hạn kết thúc
            </div>
        </div>
    </div>
</div>
