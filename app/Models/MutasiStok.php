<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiStok extends Model
{
    use HasFactory;

    protected $table = 'mutasi_stok';

    protected $fillable = [
        'barang_id',
        'jenis_mutasi',
        'jumlah',
        'stok_awal',
        'stok_akhir',
        'tanggal',
        'keterangan',
        'user_id',
    ];

    public function barang()
    {
        return $this->belongsTo(BarangAtk::class, 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
