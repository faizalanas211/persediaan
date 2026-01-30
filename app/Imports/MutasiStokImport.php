<?php

namespace App\Imports;

use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MutasiStokImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            if ($index === 0) continue; // skip header

            $namaBarang  = trim($row[0]);
            $jenisMutasi = strtolower(trim($row[1]));
            $jumlah      = (int) $row[2];
            
            if (!empty($row[3])) {
                if (is_numeric($row[3])) {
                    // Tanggal asli dari Excel (serial number)
                    $tanggal = Carbon::instance(
                        ExcelDate::excelToDateTimeObject($row[3])
                    );
                } else {
                    // Kalau string
                    $tanggal = Carbon::parse($row[3]);
                }
            } else {
                // Kalau kosong
                $tanggal = Carbon::today();
            }

            $keterangan  = $row[4] ?? null;

            if (!$namaBarang) {
                throw ValidationException::withMessages([
                    'file' => "Nama barang kosong di baris " . ($index + 1)
                ]);
            }

            if (!in_array($jenisMutasi, ['masuk', 'keluar', 'penyesuaian'])) {
                throw ValidationException::withMessages([
                    'file' => "Jenis mutasi tidak valid di baris " . ($index + 1)
                ]);
            }

            if ($jumlah <= 0) {
                throw ValidationException::withMessages([
                    'file' => "Jumlah tidak valid di baris " . ($index + 1)
                ]);
            }

            $barang = BarangAtk::lockForUpdate()
                ->whereRaw('LOWER(nama_barang) = ?', [strtolower($namaBarang)])
                ->first();

            if (!$barang) {
                throw ValidationException::withMessages([
                    'file' => "Barang '{$namaBarang}' tidak ditemukan (baris " . ($index + 1) . ")"
                ]);
            }

            $stokAwal = $barang->stok;

            if ($jenisMutasi === 'masuk') {
                $stokAkhir = $stokAwal + $jumlah;
            } elseif ($jenisMutasi === 'keluar') {
                if ($stokAwal < $jumlah) {
                    throw ValidationException::withMessages([
                        'file' => "Stok '{$namaBarang}' tidak mencukupi (baris " . ($index + 1) . ")"
                    ]);
                }
                $stokAkhir = $stokAwal - $jumlah;
            } else {
                $stokAkhir = $jumlah;
            }

            $barang->update(['stok' => $stokAkhir]);

            MutasiStok::create([
                'barang_id'    => $barang->id,
                'jenis_mutasi' => $jenisMutasi,
                'jumlah'       => $jumlah,
                'stok_awal'    => $stokAwal,
                'stok_akhir'   => $stokAkhir,
                'tanggal'      => $tanggal,
                'keterangan'   => $keterangan,
                'user_id'      => Auth::id(),
            ]);
        }
    }
}
