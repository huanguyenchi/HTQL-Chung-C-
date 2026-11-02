{{-- Mã VietQR thanh toán hóa đơn: $hoaDon --}}
<div class="card">
    <div class="card-header"><i class="bi bi-qr-code me-1"></i>Quét mã để thanh toán</div>
    <div class="card-body text-center">
        <div class="d-inline-block p-2 bg-white rounded-3 border">
            <div class="vietqr" data-qr="{{ $hoaDon->vietQrPayload() }}" style="width:220px;height:220px"
                 role="img" aria-label="Mã QR thanh toán hóa đơn #{{ $hoaDon->id_hoadon }}"></div>
            <div class="small fw-semibold mt-1" style="color:var(--ocean-brand)">VietQR · NAPAS 247</div>
        </div>
        <p class="small text-muted mt-2 mb-3">Mở app ngân hàng, chọn <strong>Quét QR</strong>. Số tiền và nội dung đã được điền sẵn.</p>
        <dl class="dl-ocean text-start mb-0">
            <dt>Ngân hàng</dt><dd>{{ config('thanhtoan.bank_name') }}</dd>
            <dt>Số tài khoản</dt><dd>{{ config('thanhtoan.account_no') }}</dd>
            <dt>Chủ tài khoản</dt><dd>{{ config('thanhtoan.account_name') }}</dd>
            <dt>Số tiền</dt><dd class="money">@vnd($hoaDon->thanhtien)</dd>
            <dt>Nội dung</dt><dd><code>{{ $hoaDon->noiDungChuyenKhoan() }}</code></dd>
        </dl>
        <div class="small text-muted mt-2">Ban quản lý sẽ xác nhận sau khi nhận được tiền.</div>
    </div>
</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.js"></script>
        <script>
            document.querySelectorAll('.vietqr').forEach(function (el) {
                var qr = qrcode(0, 'M');
                qr.addData(el.dataset.qr);
                qr.make();
                el.innerHTML = qr.createSvgTag({ cellSize: 4, margin: 0, scalable: true });
                var svg = el.querySelector('svg');
                if (svg) { svg.style.width = '100%'; svg.style.height = '100%'; }
            });
        </script>
    @endpush
@endonce
