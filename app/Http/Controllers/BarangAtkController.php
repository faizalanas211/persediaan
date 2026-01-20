<?php

namespace App\Http\Controllers;

use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangAtkController extends Controller
{
    public function index()
    {
        $barangs = BarangAtk::withExists('detailPermintaan')->orderBy('nama_barang', 'asc')
                    ->paginate(20); 
        return view('dashboard.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('dashboard.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'nullable|integer|min:0',
        ]);

        $satuan = $request->satuan === 'lainnya'
            ? $request->satuan_lainnya
            : $request->satuan;

        DB::transaction(function () use ($request, $satuan) {

            $stokAwal = 0;
            $jumlah   = $request->stok ?? 0;
            $stokAkhir = $stokAwal + $jumlah;

            // Simpan barang
            $barang = BarangAtk::create([
                'nama_barang' => $request->nama_barang,
                'satuan'      => $satuan,
                'stok'        => $stokAkhir,
            ]);

            // Simpan mutasi stok awal (jika ada stok)
            if ($jumlah > 0) {
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'masuk',
                    'jumlah'       => $jumlah,
                    'stok_awal'    => $stokAwal,
                    'stok_akhir'   => $stokAkhir,
                    'tanggal'      => now(),
                    'keterangan'   => 'Stok awal barang',
                    'user_id'      => Auth::id(),
                ]);
            }

        });

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan dan stok awal tercatat di mutasi.');
    }

    public function edit(BarangAtk $barang)
    {
        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, BarangAtk $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'required|string|max:50',
        ]);

        $satuan = $request->satuan === 'lainnya'
            ? $request->satuan_lainnya
            : $request->satuan;

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'satuan'      => $satuan,
        ]);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(BarangAtk $barang)
    {
        if ($barang->detailPermintaan()->exists()) {
            return back()->withErrors([
                'error' => 'Barang tidak dapat dihapus karena sudah memiliki riwayat permintaan'
            ]);
        }

        $barang->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil dihapus!');
    }

    public function riwayat(BarangAtk $barang)
    {
        $mutasi = $barang->mutasiStok()
                        ->orderBy('tanggal')
                        ->orderBy('id')
                        ->get();

        return view('dashboard.barang.riwayat', compact('barang', 'mutasi'));
    }
}
