<?php

namespace App\Imports;

use App\Models\BarangAtk;
use App\Models\StokOpname;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;

class StokOpnameImport implements ToCollection
{
    protected $stokOpname;

    public function __construct(StokOpname $stokOpname)
    {
        $this->stokOpname = $stokOpname;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            if ($index === 0) continue; // skip header

            $namaBarang = trim($row[0] ?? '');
            $stokFisik  = (int) ($row[1] ?? 0);
            $keterangan = $row[2] ?? null;

            if (!$namaBarang) {
                throw ValidationException::withMessages([
                    'file' => "Nama barang kosong di baris ".($index + 1)
                ]);
            }

            if ($stokFisik < 0) {
                throw ValidationException::withMessages([
                    'file' => "Stok fisik tidak valid di baris ".($index + 1)
                ]);
            }

            $barang = BarangAtk::whereRaw(
                'LOWER(nama_barang) = ?',
                [strtolower($namaBarang)]
            )->first();

            if (!$barang) {
                throw ValidationException::withMessages([
                    'file' => "Barang '{$namaBarang}' tidak ditemukan (baris ".($index + 1).")"
                ]);
            }

            $stokSistem = $barang->stok;
            $selisih    = $stokFisik - $stokSistem;

            $this->stokOpname->detail()->create([
                'barang_id'   => $barang->id,
                'stok_sistem' => $stokSistem,
                'stok_fisik'  => $stokFisik,
                'selisih'     => $selisih,
                'keterangan'  => $keterangan,
            ]);
        }
    }
}
