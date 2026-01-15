<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanAtk extends Model
{
    use HasFactory;

    protected $table = 'permintaan_atk';

    protected $fillable = [
        'nama_pemohon',
        'nip_pemohon',
        'bagian_pemohon',
        'tanggal_permintaan',
        'keperluan',
        'keterangan',
        'status',
        'dicatat_oleh'
    ];

    public function detail()
    {
        return $this->hasMany(DetailPermintaanAtk::class, 'permintaan_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

}
