<?php

namespace App\Imports;

use App\Models\BarangAtk;
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
        return new BarangAtk([
            'nama_barang' => $row['nama_barang'],
            'satuan'      => $row['satuan'],
            'stok'        => $row['stok'] ?? 0,
        ]);
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
