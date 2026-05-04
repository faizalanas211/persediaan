<?php

namespace App\Http\Controllers;

use App\Imports\MutasiStokImport;
use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class MutasiStokController extends Controller
{
    public function index(Request $request)
    {
        $query = MutasiStok::with(['barang', 'user']);

        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_mutasi', $request->jenis);
        }

        // ✅ REVISI: Filter bulan lebih aman pakai Carbon
        if ($request->filled('bulan')) {
            try {
                $date = Carbon::parse($request->bulan);
                $query->whereMonth('tanggal', $date->month)
                    ->whereYear('tanggal', $date->year);
            } catch (\Exception $e) {
                // Jika format bulan tidak valid, abaikan filter
            }
        }

        $mutasi = $query
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.mutasi.index', compact('mutasi'));
    }

    /**
     * Form mutasi stok
     */
    public function create()
    {
        $barangs = BarangAtk::orderBy('nama_barang')->get();
        return view('dashboard.mutasi.create', compact('barangs'));
    }

    /**
     * Simpan mutasi stok
     */
    public function store(Request $request)
    {
        // ✅ REVISI: Validasi dinamis berdasarkan jenis_mutasi
        $rules = [
            'barang_id'     => 'required|exists:barang_atk,id',
            'jenis_mutasi'  => 'required|in:masuk,keluar,penyesuaian',
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
        ];

        if ($request->jenis_mutasi === 'penyesuaian') {
            $rules['jumlah'] = 'required|integer|min:0'; // penyesuaian bisa 0
        } else {
            $rules['jumlah'] = 'required|integer|min:1'; // masuk/keluar minimal 1
        }

        $request->validate($rules);

        DB::transaction(function () use ($request) {

            $barang = BarangAtk::lockForUpdate()->findOrFail($request->barang_id);
            $stokAwal = $barang->stok;

            if ($request->jenis_mutasi === 'masuk') {
                $stokAkhir = $stokAwal + $request->jumlah;

            } elseif ($request->jenis_mutasi === 'keluar') {
                if ($stokAwal < $request->jumlah) {
                    throw ValidationException::withMessages([
                        'jumlah' => "Stok tidak mencukupi (Stok saat ini: {$stokAwal} {$barang->satuan})"
                    ]);
                }
                $stokAkhir = $stokAwal - $request->jumlah;

            } else { // penyesuaian
                // ✅ REVISI: Penyesuaian bisa menaikkan atau menurunkan stok ke nilai tertentu
                $stokAkhir = $request->jumlah;
            }

            // update stok barang
            $barang->update(['stok' => $stokAkhir]);

            // simpan mutasi
            MutasiStok::create([
                'barang_id'    => $barang->id,
                'jenis_mutasi' => $request->jenis_mutasi,
                'jumlah'       => $request->jumlah,
                'stok_awal'    => $stokAwal,
                'stok_akhir'   => $stokAkhir,
                'tanggal'      => $request->tanggal,
                'keterangan'   => $request->keterangan,
                'user_id'      => Auth::id(),
            ]);
        });

        return redirect()
            ->route('mutasi.index')
            ->with('success', 'Mutasi stok berhasil dicatat!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        DB::beginTransaction();

        try {
            Excel::import(new MutasiStokImport, $request->file('file'));

            DB::commit();

            return redirect()
                ->route('mutasi.index')
                ->with('success', 'Data mutasi stok berhasil diimport!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'file' => 'Gagal import: ' . $e->getMessage()
            ]);
        }
    }
}