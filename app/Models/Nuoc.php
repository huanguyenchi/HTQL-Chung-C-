<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nuoc extends Model
{
    protected $table = 'nuoc';
    protected $primaryKey = 'id_nuoc';
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
