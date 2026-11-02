<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toa extends Model
{
    protected $table = 'toa';
    protected $primaryKey = 'id_toa';
    public $timestamps = false;
    protected $fillable = ['ten_toa'];

    public function tangs()
    {
        return $this->hasMany(Tang::class, 'id_toa', 'id_toa');
    }
}
