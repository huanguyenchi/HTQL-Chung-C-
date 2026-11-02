<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const QUAN_LY = 1;
    public const CU_DAN = 2;

    public const HOAT_DONG = 1;
    public const BI_KHOA = 0;
    public const CHO_DUYET = 2;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = ['ten', 'ngaysinh', 'email', 'sdt', 'stk', 'ghichu_dangky', 'password', 'quyen', 'trangthai'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'ngaysinh' => 'date',
            'password' => 'hashed',
            'quyen' => 'integer',
            'trangthai' => 'integer',
        ];
    }

    public function isQuanLy(): bool
    {
        return (int) $this->quyen === self::QUAN_LY;
    }

    public function tenQuyen(): string
    {
        return $this->isQuanLy() ? 'Quản lý' : 'Cư dân';
    }

    public function choDuyet(): bool
    {
        return (int) $this->trangthai === self::CHO_DUYET;
    }

    public function phong()
    {
        return $this->hasOne(Phong::class, 'id_user', 'id_user');
    }

    public function hopDongs()
    {
        return $this->hasMany(HopDong::class, 'id_user', 'id_user');
    }
}
