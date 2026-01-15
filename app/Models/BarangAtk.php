<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangAtk extends Model
{
    use HasFactory;

    protected $table = 'barang_atk';

    protected $fillable = [
        'nama_barang',
        'satuan',
        'stok',
    ];

    public function mutasiStok()
    {
        return $this->hasMany(MutasiStok::class, 'barang_id');
    }

    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaanAtk::class, 'barang_id');
    }

}
