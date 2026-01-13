<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPermintaanAtk extends Model
{
    use HasFactory;

    protected $table = 'detail_permintaan_atk';

    protected $fillable = [
        'permintaan_id',
        'barang_id',
        'jumlah',
    ];

    public function permintaanAtk()
    {
        return $this->belongsTo(PermintaanAtk::class, 'permintaan_id');
    }

    public function barang()
    {
        return $this->belongsTo(BarangAtk::class, 'barang_id');
    }
}
