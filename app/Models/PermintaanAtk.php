<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanAtk extends Model
{
    use HasFactory;

    protected $table = 'permintaan_atk';

    protected $fillable = [
        'pegawai_id',
        'tanggal_permintaan',
        'keperluan',
        'keterangan',
        'dicatat_oleh'
    ];

    public function detail()
    {
        return $this->hasMany(DetailPermintaanAtk::class, 'permintaan_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

}
