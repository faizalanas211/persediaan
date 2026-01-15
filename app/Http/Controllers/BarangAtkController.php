<?php

namespace App\Http\Controllers;

use App\Models\BarangAtk;
use Illuminate\Http\Request;
use App\Imports\BarangAtkImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class BarangAtkController extends Controller
{
    public function index()
    {
        $barangs = BarangAtk::orderBy('nama_barang', 'asc')
            ->withExists('detailPermintaan')
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
            'nama_barang'      => 'required|string|max:255',
            'satuan'           => 'required|string|max:50',
            'satuan_lainnya'   => 'required_if:satuan,lainnya|max:50',
            'stok'             => 'nullable|integer|min:0',
        ]);

        $satuan = $request->satuan === 'lainnya'
            ? $request->satuan_lainnya
            : $request->satuan;

        BarangAtk::create([
            'nama_barang' => $request->nama_barang,
            'satuan'      => $satuan,
            'stok'        => $request->stok ?? 0,
        ]);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit(BarangAtk $barang)
    {
        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, BarangAtk $barang)
    {
        $request->validate([
            'nama_barang'      => 'required|string|max:255',
            'satuan'           => 'required|string|max:50',
            'satuan_lainnya'   => 'required_if:satuan,lainnya|max:50',
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        DB::beginTransaction();

        try {
            Excel::import(new BarangAtkImport, $request->file('file'));

            DB::commit();

            return redirect()
                ->route('barang.index')
                ->with('success', 'Data barang berhasil diimport dari Excel!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'file' => 'Gagal import: ' . $e->getMessage()
            ]);
        }
    }
}
