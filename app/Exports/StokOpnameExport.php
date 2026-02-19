<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StokOpnameExport implements FromCollection, WithHeadings
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

                return [
                    'no'            => $index + 1,
                    'nama_barang'   => $item->barang->nama_barang ?? '-',
                    'satuan'        => $item->barang->satuan ?? '-',
                    'stok_sistem'   => $item->stok_sistem,
                    'stok_fisik'    => $item->stok_fisik,
                    'selisih'       => $item->selisih ?? 0,
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
