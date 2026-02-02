<?php

namespace App\Imports;

use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};

class BarangAtkImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $stokAwal = (int) ($row['stok'] ?? 0);

        $barang = new BarangAtk([
            'nama_barang' => $row['nama_barang'],
            'satuan'      => $row['satuan'],
            'stok'        => $stokAwal,
        ]);

        // setelah barang tersimpan, buat mutasi masuk
        $barang->saved(function ($barang) use ($stokAwal) {

            if ($stokAwal > 0) {
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'masuk',
                    'jumlah'       => $stokAwal,
                    'stok_awal'    => 0,
                    'stok_akhir'   => $stokAwal,
                    'tanggal'      => Carbon::now(),
                    'keterangan'   => 'Stok awal dari import Excel',
                    'user_id'      => Auth::id(),
                ]);
            }

        });

        return $barang;
    }

    public function rules(): array
    {
        return [
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'nullable|integer|min:0',
        ];
    }
}
