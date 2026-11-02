@extends('layouts.quanly')
@section('title', $phong->exists ? 'Sửa phòng' : 'Thêm phòng')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.phong.index') }}">Quản lý phòng</a></li><li class="breadcrumb-item active">{{ $phong->exists ? 'Sửa' : 'Thêm mới' }}</li></ol></nav>
            <h1>{{ $phong->exists ? 'Sửa phòng ' . $phong->ten_phong : 'Thêm phòng mới' }}</h1>
        </div>
    </div>
    <div class="card" style="max-width:560px">
        <div class="card-body">
            <form method="POST" action="{{ $phong->exists ? route('quanly.phong.update', $phong) : route('quanly.phong.store') }}">
                @csrf
                @if ($phong->exists) @method('PUT') @endif
                <div class="mb-3">
                    <label class="form-label" for="id_tang">Thuộc tầng</label>
                    <select id="id_tang" name="id_tang" class="form-select @error('id_tang') is-invalid @enderror">
                        <option value="">-- Chọn tầng --</option>
                        @foreach ($toas as $toa)
                            <optgroup label="Tòa {{ $toa->ten_toa }}">
                                @foreach ($toa->tangs as $tang)
                                    <option value="{{ $tang->id_tang }}" @selected(old('id_tang', $phong->id_tang) == $tang->id_tang)>Tòa {{ $toa->ten_toa }} · {{ $tang->ten_tang }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('id_tang')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="ten_phong">Tên phòng</label>
                    <input id="ten_phong" name="ten_phong" value="{{ old('ten_phong', $phong->ten_phong) }}" class="form-control @error('ten_phong') is-invalid @enderror" placeholder="Ví dụ: A101">
                    @error('ten_phong')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="id_user">Cư dân đang ở</label>
                    <select id="id_user" name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                        <option value="">-- Phòng trống --</option>
                        <option value="moi" @selected(old('id_user') === 'moi')>+ Thêm cư dân mới</option>
                        @foreach ($cuDans as $cd)
                            @php $phongKhac = $cd->phong && $cd->phong->id_phong != $phong->id_phong ? $cd->phong : null; @endphp
                            <option value="{{ $cd->id_user }}" @selected((string) old('id_user', $phong->id_user) === (string) $cd->id_user) @disabled($phongKhac)>
                                {{ $cd->ten }} ({{ $cd->email }}){{ $phongKhac ? ' – đang ở phòng ' . $phongKhac->ten_phong : '' }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Chọn cư dân có sẵn, chọn "Thêm cư dân mới" để nhập người mới, hoặc để trống nếu phòng chưa có người ở.</div>
                    @error('id_user')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3" id="o-songuoi" style="{{ old('id_user', $phong->id_user) ? '' : 'display:none' }}">
                    <label class="form-label" for="songuoi">Số người ở</label>
                    <input type="number" id="songuoi" name="songuoi" min="1" max="10" value="{{ old('songuoi', $phong->songuoi ?: 1) }}" class="form-control @error('songuoi') is-invalid @enderror" style="max-width:140px">
                    <div class="form-text">Tính cả chủ hộ và người ở cùng.</div>
                    @error('songuoi')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div id="cudan-moi" class="p-3 mb-4 rounded-3" style="background:var(--ocean-mist);{{ old('id_user') === 'moi' ? '' : 'display:none' }}">
                    <div class="fw-bold mb-2" style="color:var(--ocean-navy)"><i class="bi bi-person-plus me-1"></i>Thông tin cư dân mới</div>
                    <div class="row g-2">
                        <div class="col-sm-7">
                            <label class="form-label" for="moi_ten">Họ tên</label>
                            <input id="moi_ten" name="moi_ten" value="{{ old('moi_ten') }}" class="form-control @error('moi_ten') is-invalid @enderror">
                            @error('moi_ten')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label" for="moi_ngaysinh">Ngày sinh</label>
                            <input type="date" id="moi_ngaysinh" name="moi_ngaysinh" value="{{ old('moi_ngaysinh') }}" class="form-control @error('moi_ngaysinh') is-invalid @enderror">
                            @error('moi_ngaysinh')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label" for="moi_email">Email đăng nhập</label>
                            <input type="email" id="moi_email" name="moi_email" value="{{ old('moi_email') }}" class="form-control @error('moi_email') is-invalid @enderror">
                            @error('moi_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-5">
                            <label class="form-label" for="moi_sdt">Số điện thoại</label>
                            <input id="moi_sdt" name="moi_sdt" value="{{ old('moi_sdt') }}" class="form-control @error('moi_sdt') is-invalid @enderror" placeholder="09xxxxxxxx">
                            @error('moi_sdt')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-7">
                            <label class="form-label" for="moi_password">Mật khẩu</label>
                            <input type="text" id="moi_password" name="moi_password" value="{{ old('moi_password', '123456') }}" class="form-control @error('moi_password') is-invalid @enderror">
                            @error('moi_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="form-text">Báo cho cư dân để đăng nhập, có thể đổi sau.</div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu</button>
                <a href="{{ route('quanly.phong.index') }}" class="btn btn-light-ocean">Hủy</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('id_user').addEventListener('change', function () {
        document.getElementById('cudan-moi').style.display = this.value === 'moi' ? '' : 'none';
        document.getElementById('o-songuoi').style.display = this.value ? '' : 'none';
    });
</script>
@endpush
