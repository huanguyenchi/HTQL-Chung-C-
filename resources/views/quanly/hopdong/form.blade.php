@extends('layouts.quanly')
@section('title', 'Lập hợp đồng')

@section('content')
    <div class="page-head">
        <div>
            <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('quanly.hopdong.index') }}">Quản lý hợp đồng</a></li><li class="breadcrumb-item active">Lập mới</li></ol></nav>
            <h1>Lập hợp đồng mới</h1>
            <p>Người lập: {{ auth()->user()->ten }}</p>
        </div>
    </div>
    <div class="card" style="max-width:860px">
        <div class="card-body">
            <form method="POST" action="{{ route('quanly.hopdong.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="id_user">Cư dân thuê</label>
                        <select id="id_user" name="id_user" class="form-select @error('id_user') is-invalid @enderror">
                            <option value="">-- Chọn cư dân --</option>
                            @foreach ($cuDans as $cd)
                                <option value="{{ $cd->id_user }}" @selected(old('id_user', $hopDong->id_user) == $cd->id_user)>{{ $cd->ten }} ({{ $cd->email }})</option>
                            @endforeach
                        </select>
                        @error('id_user')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="id_phong">Phòng</label>
                        <select id="id_phong" name="id_phong" class="form-select @error('id_phong') is-invalid @enderror">
                            <option value="">-- Chọn phòng --</option>
                            @foreach ($phongs as $p)
                                <option value="{{ $p->id_phong }}" @selected(old('id_phong', $hopDong->id_phong) == $p->id_phong)>
                                    {{ $p->ten_phong }} · Tòa {{ $p->tang->toa->ten_toa }}{{ $p->id_user ? ' (đang có người ở)' : ' (trống)' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_phong')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="gia">Giá thuê / tháng (VNĐ)</label>
                        <input type="number" min="0" step="50000" id="gia" name="gia" value="{{ old('gia', $hopDong->gia) }}" class="form-control @error('gia') is-invalid @enderror" placeholder="4500000">
                        @error('gia')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="ngaybatdau">Ngày bắt đầu</label>
                        <input type="date" id="ngaybatdau" name="ngaybatdau" value="{{ old('ngaybatdau', $hopDong->ngaybatdau?->format('Y-m-d')) }}" class="form-control @error('ngaybatdau') is-invalid @enderror">
                        @error('ngaybatdau')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="ngayketthuc">Ngày kết thúc</label>
                        <input type="date" id="ngayketthuc" name="ngayketthuc" value="{{ old('ngayketthuc', $hopDong->ngayketthuc?->format('Y-m-d')) }}" class="form-control @error('ngayketthuc') is-invalid @enderror">
                        @error('ngayketthuc')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="noidung">Nội dung / điều khoản</label>
                        <textarea id="noidung" name="noidung" rows="5" class="form-control @error('noidung') is-invalid @enderror" placeholder="Tiền cọc, hạn thanh toán, nội quy...">{{ old('noidung', $hopDong->noidung) }}</textarea>
                        @error('noidung')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Lưu hợp đồng</button>
                    <a href="{{ route('quanly.hopdong.index') }}" class="btn btn-light-ocean">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
