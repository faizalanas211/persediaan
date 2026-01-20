<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stok_opname';

    protected $fillable = [
        'periode_bulan',
        'tanggal_opname',
        'keterangan',
        'user_id',
        'status',
    ];

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailStokOpname::class, 'stok_opname_id');
    }

}
