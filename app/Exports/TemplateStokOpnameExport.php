<?php

namespace App\Exports;

use App\Models\BarangAtk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateStokOpnameExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Nama Barang',
            'Stok Sistem',
            'Stok Fisik',
            'Keterangan'
        ];
    }

    public function array(): array
    {
        $barangs = BarangAtk::orderBy('nama_barang')->get();

        $data = [];

        foreach ($barangs as $barang) {
            $data[] = [
                $barang->nama_barang,
                $barang->stok,
                '', // stok fisik kosong
                ''  // keterangan kosong
            ];
        }

        return $data;
    }
}
