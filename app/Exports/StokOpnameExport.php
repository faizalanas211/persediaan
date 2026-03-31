<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class StokOpnameExport implements FromCollection, WithHeadings, WithStrictNullComparison
{
    protected $stokOpname;

    public function __construct($stokOpname)
    {
        $this->stokOpname = $stokOpname;
    }

    public function collection()
    {
        return $this->stokOpname->detail()
            ->with('barang')
            ->get()
            ->map(function ($item, $index) {

                // 🔥 AMANKAN NILAI
                $stokSistem = (int) $item->stok_sistem;
                $stokFisik  = (int) $item->stok_fisik;
                $selisih    = (int) ($item->selisih ?? 0);

                return [
                    'no'            => $index + 1,
                    'nama_barang'   => $item->barang->nama_barang ?? '-',
                    'satuan'        => $item->barang->satuan ?? '-',

                    // 🔥 PAKSA STRING BIAR 0 TIDAK HILANG
                    'stok_sistem'   => (string) $stokSistem,
                    'stok_fisik'    => (string) $stokFisik,
                    'selisih'       => (string) $selisih,

                    'keterangan'    => $item->keterangan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'Nama Barang',
            'Satuan',
            'Stok Sistem',
            'Stok Fisik',
            'Selisih',
            'Keterangan',
        ];
    }
}