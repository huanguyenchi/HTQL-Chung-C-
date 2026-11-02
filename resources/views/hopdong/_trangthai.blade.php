@php $tt = $hopDong->tinhTrang(); @endphp
<span class="chip {{ ['Còn hiệu lực' => 'chip-active', 'Sắp hết hạn' => 'chip-warn', 'Chưa bắt đầu' => 'chip-muted', 'Đã hết hạn' => 'chip-muted'][$tt] }}">{{ $tt }}</span>
