<?php

namespace App\Imports;

use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // ================= CEK DUPLIKAT SEBELUM CREATE =================
        $kodeBarang = $row['kode_barang'] ?? null;

        // Jika kode_barang sudah ada di database, SKIP (jangan import)
        if ($kodeBarang && BarangAtk::where('kode_barang', $kodeBarang)->exists()) {
            return null; // Lewati baris ini
        }

        // ================= AUTO GENERATE KODE =================
        if (!$kodeBarang) {
            $last = BarangAtk::whereNotNull('kode_barang')
                ->orderByDesc('id')
                ->first();

            $number = 1;

            if ($last && preg_match('/\d+$/', $last->kode_barang, $match)) {
                $number = (int)$match[0] + 1;
            }

            $kodeBarang = 'ATK-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        }

        // Simpan barang
        $barang = BarangAtk::create([
            'kode_barang' => $kodeBarang,
            'nama_barang' => $row['nama_barang'],
            'satuan'      => $row['satuan'],
            'stok'        => $stokAwal,
        ]);

        // Mutasi hanya jika stok > 0
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

        return $barang;
    }

    public function rules(): array
    {
        return [
            'kode_barang' => 'nullable|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'nullable|integer|min:0',
        ];
    }
}