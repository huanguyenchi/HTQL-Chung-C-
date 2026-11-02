<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    public const CHUA_THANH_TOAN = 0;
    public const DA_THANH_TOAN = 1;

    protected $table = 'hoadon';
    protected $primaryKey = 'id_hoadon';
    protected $fillable = [
        'id_phong', 'id_dien', 'id_nuoc', 'thoigian', 'tienphong',
        'tiendien', 'tiennuoc', 'khac', 'thuthem', 'thanhtien', 'tinhtrang', 'ngaythanhtoan',
    ];

    protected function casts(): array
    {
        return [
            'thoigian' => 'date',
            'tinhtrang' => 'integer',
            'ngaythanhtoan' => 'datetime',
        ];
    }

    public function phong()
    {
        return $this->belongsTo(Phong::class, 'id_phong', 'id_phong');
    }

    public function dien()
    {
        return $this->belongsTo(Dien::class, 'id_dien', 'id_dien');
    }

    public function nuoc()
    {
        return $this->belongsTo(Nuoc::class, 'id_nuoc', 'id_nuoc');
    }

    public function daThanhToan(): bool
    {
        return (int) $this->tinhtrang === self::DA_THANH_TOAN;
    }

    public function hopDong()
    {
        $dau = $this->thoigian->copy()->startOfMonth()->toDateString();
        $cuoi = $this->thoigian->copy()->endOfMonth()->toDateString();

        return HopDong::with(['cuDan', 'quanLy'])
            ->where('id_phong', $this->id_phong)
            ->whereDate('ngaybatdau', '<=', $cuoi)
            ->whereDate('ngayketthuc', '>=', $dau)
            ->orderByDesc('ngaybatdau')
            ->first();
    }

    public function scopeCuaCuDan($query, int $idUser)
    {
        return $query->whereExists(function ($q) use ($idUser) {
            $q->selectRaw('1')
                ->from('hopdong')
                ->whereColumn('hopdong.id_phong', 'hoadon.id_phong')
                ->where('hopdong.id_user', $idUser)
                ->whereRaw('hopdong.ngaybatdau <= LAST_DAY(hoadon.thoigian)')
                ->whereColumn('hopdong.ngayketthuc', '>=', 'hoadon.thoigian');
        });
    }

    public function noiDungChuyenKhoan(): string
    {
        $this->loadMissing('phong');

        return 'HD' . $this->id_hoadon . ' ' . $this->phong->ten_phong . ' T' . $this->thoigian->format('mY');
    }

    public function vietQrPayload(): string
    {
        $cfg = config('thanhtoan');

        $nguoiNhan = self::tlv('00', 'A000000727')
            . self::tlv('01', self::tlv('00', (string) $cfg['bank_id']) . self::tlv('01', (string) $cfg['account_no']))
            . self::tlv('02', 'QRIBFTTA');

        $chuoi = self::tlv('00', '01')
            . self::tlv('01', '12')
            . self::tlv('38', $nguoiNhan)
            . self::tlv('53', '704')
            . self::tlv('54', (string) (int) $this->thanhtien)
            . self::tlv('58', 'VN')
            . self::tlv('62', self::tlv('08', $this->noiDungChuyenKhoan()))
            . '6304';

        return $chuoi . strtoupper(str_pad(dechex(self::crc16($chuoi)), 4, '0', STR_PAD_LEFT));
    }

    private static function tlv(string $id, string $giaTri): string
    {
        return $id . str_pad((string) strlen($giaTri), 2, '0', STR_PAD_LEFT) . $giaTri;
    }

    private static function crc16(string $data): int
    {
        $crc = 0xFFFF;
        for ($i = 0, $n = strlen($data); $i < $n; $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($b = 0; $b < 8; $b++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return $crc;
    }
}
