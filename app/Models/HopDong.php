<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HopDong extends Model
{
    protected $table = 'hopdong';
    protected $primaryKey = 'id_hopdong';
    protected $fillable = ['id_user', 'id_ql', 'id_phong', 'noidung', 'gia', 'ngaybatdau', 'ngayketthuc'];

    protected function casts(): array
    {
        return [
            'ngaybatdau' => 'date',
            'ngayketthuc' => 'date',
            'gia' => 'integer',
        ];
    }

    public function cuDan()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function quanLy()
    {
        return $this->belongsTo(User::class, 'id_ql', 'id_user');
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'id_phong', 'id_phong');
    }

    public function scopeHieuLuc($query, $ngay = null)
    {
        $ngay = ($ngay ?? now())->toDateString();

        return $query->whereDate('ngaybatdau', '<=', $ngay)->whereDate('ngayketthuc', '>=', $ngay);
    }

    public function conHieuLuc(): bool
    {
        $homNay = now()->startOfDay();

        return $this->ngaybatdau->lte($homNay) && $this->ngayketthuc->gte($homNay);
    }

    public function sapHetHan(): bool
    {
        return $this->conHieuLuc() && $this->ngayketthuc->lte(now()->addDays(30));
    }

    public function tinhTrang(): string
    {
        if ($this->ngaybatdau->isFuture()) {
            return 'Chưa bắt đầu';
        }

        return $this->conHieuLuc() ? ($this->sapHetHan() ? 'Sắp hết hạn' : 'Còn hiệu lực') : 'Đã hết hạn';
    }
}
