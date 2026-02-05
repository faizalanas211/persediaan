<?php

namespace App\Exports;

use App\Models\StokOpname;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StokOpnameExport implements FromArray, WithHeadings, WithTitle
{
    protected $stokOpname;

    public function __construct(StokOpname $stokOpname)
    {
        $this->stokOpname = $stokOpname;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Satuan',
            'Stok Sistem',
            'Stok Fisik',
            'Selisih',
            'Keterangan',
        ];
    }

    public function array(): array
    {
        $data = [];
        $no = 1;

        foreach ($this->stokOpname->detail as $d) {
            $data[] = [
                $no++,
                $d->barang->nama_barang,
                $d->barang->satuan,
                $d->stok_sistem,
                $d->stok_fisik,
                $d->selisih,
                $d->keterangan ?? '-',
            ];
        }

        return $data;
    }

    public function title(): string
    {
        return 'Stok Opname ' . $this->stokOpname->periode_bulan;
    }
}
