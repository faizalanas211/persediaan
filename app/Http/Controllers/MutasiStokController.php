<?php

namespace App\Http\Controllers;

use App\Imports\MutasiStokImport;
use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
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

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', substr($request->bulan, 5, 2))
                ->whereYear('tanggal', substr($request->bulan, 0, 4));
        }

        $mutasi = $query
            ->orderByDesc('updated_at')
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
        $request->validate([
            'barang_id'     => 'required|exists:barang_atk,id',
            'jenis_mutasi'  => 'required|in:masuk,keluar,penyesuaian',
            'jumlah'        => 'required|integer|min:1',
            'tanggal'       => 'required|date',
            'keterangan'    => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

            $barang = BarangAtk::lockForUpdate()->findOrFail($request->barang_id);

            $stokAwal = $barang->stok;

            if ($request->jenis_mutasi === 'masuk') {
                $stokAkhir = $stokAwal + $request->jumlah;

            } elseif ($request->jenis_mutasi === 'keluar') {
                if ($stokAwal < $request->jumlah) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Stok tidak mencukupi'
                    ]);
                }
                $stokAkhir = $stokAwal - $request->jumlah;

            } else { // penyesuaian
                $stokAkhir = $request->jumlah;
            }

            // update stok barang
            $barang->update([
                'stok' => $stokAkhir
            ]);

            // simpan mutasi + stok awal & akhir
            MutasiStok::create([
                'barang_id'   => $barang->id,
                'jenis_mutasi'=> $request->jenis_mutasi,
                'jumlah'      => $request->jumlah,
                'stok_awal'   => $stokAwal,
                'stok_akhir'  => $stokAkhir,
                'tanggal'     => $request->tanggal,
                'keterangan'  => $request->keterangan,
                'user_id'     => Auth::id(),
            ]);
        });
        } catch (ValidationException $e) {
            throw $e; // biar balik ke form
        }

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
