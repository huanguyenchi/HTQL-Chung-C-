@extends('layouts.quanly')
@php
    $sua = $hoaDon !== null;
    $khoa = $sua && $hoaDon->daThanhToan();
@endphp
@section('title', $sua ? 'Cập nhật hóa đơn điện nước' : 'Thêm hóa đơn điện nước')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item">Quản lý điện nước</li><li class="breadcrumb-item active">{{ $sua ? 'Cập nhật hóa đơn' : 'Thêm hóa đơn' }}</li></ol></nav>
            <h1>{{ $sua ? 'Cập nhật hóa đơn #' . $hoaDon->id_hoadon : 'Thêm hóa đơn điện nước' }}</h1>
            <p>{{ $sua ? 'Sửa chỉ số hoặc đơn giá khi nhập sai; hệ thống tính lại tổng tiền.' : 'Nhập chỉ số đồng hồ của phòng trong tháng; hệ thống tự tính tiền và lập hóa đơn.' }}</p>
        </div>
        @unless ($sua)
            <a href="{{ route('quanly.hoadon.index', ['tinhtrang' => 0]) }}" class="btn btn-light-ocean"><i class="bi bi-pencil-square me-1"></i>Cập nhật hóa đơn đã có</a>
        @endunless
    </div>

    @if ($khoa)
        <div class="alert alert-info"><i class="bi bi-lock-fill me-1"></i>Hóa đơn này đã thanh toán nên không thể cập nhật.</div>
    @endif

    <form method="POST" id="form-diennuoc" action="{{ $sua ? route('quanly.diennuoc.update', $hoaDon) : route('quanly.diennuoc.store') }}">
        @csrf
        @if ($sua) @method('PUT') @endif
        <fieldset @disabled($khoa)>
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-header">1. Phòng và tháng</div>
                    <div class="card-body">
                        @if ($sua)
                            <div class="row">
                                <div class="col-sm-8"><div class="text-muted small">Phòng</div><div class="fw-semibold">{{ $hoaDon->phong->ten_day_du }}</div></div>
                                <div class="col-sm-4"><div class="text-muted small">Tháng</div><div class="fw-semibold">{{ $hoaDon->thoigian->format('m/Y') }}</div></div>
                            </div>
                        @else
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="thang">Tháng</label>
                                    <input type="month" id="thang" name="thang" value="{{ old('thang', $macDinh['thang']) }}" class="form-control @error('thang') is-invalid @enderror">
                                    @error('thang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label" for="id_phong">Phòng (đã có hợp đồng)</label>
                                    <select id="id_phong" name="id_phong" class="form-select @error('id_phong') is-invalid @enderror">
                                        <option value="">-- Chọn phòng --</option>
                                        @foreach ($phongs->groupBy(fn ($p) => 'Tòa ' . $p->tang->toa->ten_toa) as $toa => $ds)
                                            <optgroup label="{{ $toa }}">
                                                @foreach ($ds as $p)
                                                    <option value="{{ $p->id_phong }}" @selected(old('id_phong', $macDinh['id_phong']) == $p->id_phong)>{{ $p->ten_phong }} · {{ $p->tang->ten_tang }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('id_phong')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">2. Chỉ số điện nước</div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach (['dien' => ['Điện', 'bi-lightning-charge-fill', 'kWh', 3500], 'nuoc' => ['Nước', 'bi-droplet-fill', 'm³', 15000]] as $loai => $cfg)
                                @php
                                    [$nhan, $icon, $donVi, $giaMacDinh] = $cfg;
                                    $bg = $sua ? $hoaDon->{$loai} : null;
                                @endphp
                                <div class="col-md-6">
                                    <div class="meter-group h-100">
                                        <h6><i class="bi {{ $icon }}"></i>{{ $nhan }} <small class="text-muted fw-normal">({{ $donVi }})</small></h6>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label" for="{{ $loai }}_cu">Chỉ số cũ</label>
                                                <input type="number" min="0" id="{{ $loai }}_cu" name="{{ $loai }}_cu" data-calc value="{{ old($loai . '_cu', $bg?->chiso_cu ?? 0) }}" class="form-control @error($loai . '_cu') is-invalid @enderror">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label" for="{{ $loai }}_moi">Chỉ số mới</label>
                                                <input type="number" min="0" id="{{ $loai }}_moi" name="{{ $loai }}_moi" data-calc value="{{ old($loai . '_moi', $bg?->chiso_moi) }}" class="form-control @error($loai . '_moi') is-invalid @enderror">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label" for="{{ $loai }}_dongia">Đơn giá (đ/{{ $donVi }})</label>
                                                <input type="number" min="1" id="{{ $loai }}_dongia" name="{{ $loai }}_dongia" data-calc value="{{ old($loai . '_dongia', $bg?->dongia ?? $giaMacDinh) }}" class="form-control @error($loai . '_dongia') is-invalid @enderror">
                                            </div>
                                        </div>
                                        @foreach (['_cu', '_moi', '_dongia'] as $s)
                                            @error($loai . $s)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-md-8">
                                <label class="form-label" for="khac">Khoản thu thêm (ghi chú)</label>
                                <input id="khac" name="khac" value="{{ old('khac', $hoaDon?->khac) }}" class="form-control @error('khac') is-invalid @enderror" placeholder="Ví dụ: Phí vệ sinh">
                                @error('khac')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="thuthem">Số tiền thu thêm</label>
                                <input type="number" min="0" id="thuthem" name="thuthem" data-calc value="{{ old('thuthem', $hoaDon?->thuthem ?? 0) }}" class="form-control @error('thuthem') is-invalid @enderror">
                                @error('thuthem')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card" style="position:sticky;top:84px">
                    <div class="card-header">Tạm tính</div>
                    <div class="card-body">
                        <div class="preview-box">
                            <div class="row-line"><span>Tiền phòng <small class="text-muted" id="cu-dan"></small></span><span class="money" id="tien-phong">0 đ</span></div>
                            <div class="row-line"><span>Tiền điện <small class="text-muted" id="sl-dien"></small></span><span class="money" id="tien-dien">0 đ</span></div>
                            <div class="row-line"><span>Tiền nước <small class="text-muted" id="sl-nuoc"></small></span><span class="money" id="tien-nuoc">0 đ</span></div>
                            <div class="row-line"><span>Thu thêm</span><span class="money" id="tien-them">0 đ</span></div>
                            <div class="row-line grand"><span>Tổng cộng</span><span class="money" id="tong">0 đ</span></div>
                        </div>
                        <div id="canh-bao" class="small text-danger mt-2 d-none"><i class="bi bi-exclamation-circle me-1"></i>Chỉ số mới đang nhỏ hơn chỉ số cũ.</div>
                        @unless ($khoa)
                            <button class="btn btn-cta w-100 mt-3 py-2"><i class="bi bi-check2-circle me-1"></i>{{ $sua ? 'Cập nhật hóa đơn' : 'Lưu và lập hóa đơn' }}</button>
                        @endunless
                        <a href="{{ $sua ? route('quanly.hoadon.show', $hoaDon) : route('quanly.hoadon.index') }}" class="btn btn-light-ocean w-100 mt-2">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
        </fieldset>
    </form>
@endsection

@push('scripts')
<script>
(function () {
    const $ = id => document.getElementById(id);
    const num = id => parseInt($(id)?.value || '0', 10) || 0;
    const vnd = n => n.toLocaleString('vi-VN') + ' đ';
    let tienPhong = {{ (int) ($hoaDon?->tienphong ?? 0) }};

    function tinh() {
        const slDien = num('dien_moi') - num('dien_cu');
        const slNuoc = num('nuoc_moi') - num('nuoc_cu');
        const tienDien = Math.max(slDien, 0) * num('dien_dongia');
        const tienNuoc = Math.max(slNuoc, 0) * num('nuoc_dongia');
        const them = num('thuthem');
        $('sl-dien').textContent = '(' + Math.max(slDien, 0) + ' kWh)';
        $('sl-nuoc').textContent = '(' + Math.max(slNuoc, 0) + ' m³)';
        $('tien-dien').textContent = vnd(tienDien);
        $('tien-nuoc').textContent = vnd(tienNuoc);
        $('tien-them').textContent = vnd(them);
        $('tien-phong').textContent = vnd(tienPhong);
        $('tong').textContent = vnd(tienPhong + tienDien + tienNuoc + them);
        const moiDien = $('dien_moi').value !== '', moiNuoc = $('nuoc_moi').value !== '';
        $('canh-bao').classList.toggle('d-none', !((moiDien && slDien < 0) || (moiNuoc && slNuoc < 0)));
    }

    document.querySelectorAll('[data-calc]').forEach(el => el.addEventListener('input', tinh));
    tinh();

    // Thêm hóa đơn: tự điền chỉ số cũ từ tháng trước
    const phong = $('id_phong'), thang = $('thang');
    if (phong && thang) {
        async function layChiSoCu() {
            if (!phong.value || !thang.value) return;
            const url = @json(route('quanly.diennuoc.chisocu')) + '?id_phong=' + phong.value + '&thang=' + thang.value;
            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                $('dien_cu').value = data.dien;
                $('nuoc_cu').value = data.nuoc;
                if (data.dien_dongia) $('dien_dongia').value = data.dien_dongia;
                if (data.nuoc_dongia) $('nuoc_dongia').value = data.nuoc_dongia;
                tienPhong = data.tienphong || 0;
                $('cu-dan').textContent = data.cudan ? '(' + data.cudan + ')' : '(phòng chưa có hợp đồng)';
                tinh();
            } catch (e) { /* giữ nguyên giá trị đang nhập */ }
        }
        phong.addEventListener('change', layChiSoCu);
        thang.addEventListener('change', layChiSoCu);
        layChiSoCu();
    }
})();
</script>
@endpush
