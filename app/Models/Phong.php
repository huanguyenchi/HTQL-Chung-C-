<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    protected $table = 'phong';
    protected $primaryKey = 'id_phong';
    public $timestamps = false;
    protected $fillable = ['id_tang', 'ten_phong', 'id_user', 'songuoi'];

    public function tang()
    {
        return $this->belongsTo(Tang::class, 'id_tang', 'id_tang');
    }

    public function cuDan()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function hopDongs()
    {
        return $this->hasMany(HopDong::class, 'id_phong', 'id_phong');
    }

    public function getTenDayDuAttribute(): string
    {
        $tang = $this->tang;
        $toa = $tang?->toa;

        return trim(($toa ? 'Tòa ' . $toa->ten_toa . ' · ' : '') . ($tang ? $tang->ten_tang . ' · ' : '') . 'Phòng ' . $this->ten_phong);
    }
}
