<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tang extends Model
{
    protected $table = 'tang';
    protected $primaryKey = 'id_tang';
    public $timestamps = false;
    protected $fillable = ['id_toa', 'ten_tang'];

    public function toa()
    {
        return $this->belongsTo(Toa::class, 'id_toa', 'id_toa');
    }

    public function phongs()
    {
        return $this->hasMany(Phong::class, 'id_tang', 'id_tang');
    }
}
