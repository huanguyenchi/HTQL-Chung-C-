<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dien extends Model
{
    protected $table = 'dien';
    protected $primaryKey = 'id_dien';
    public $timestamps = false;
    protected $fillable = ['id_phong', 'thoigian', 'chiso_cu', 'chiso_moi', 'dongia'];

    protected function casts(): array
    {
        return ['thoigian' => 'date'];
    }

    public function soTieuThu(): int
    {
        return (int) $this->chiso_moi - (int) $this->chiso_cu;
    }
}
