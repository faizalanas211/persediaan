<?php

namespace App\Http\Controllers;

use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use App\Imports\BarangAtkImport;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BarangAtkController extends Controller
{
    // public function index()
    // {
    //     $barangs = BarangAtk::orderBy('nama_barang', 'asc')
    //         ->withExists('detailPermintaan')
    //         ->paginate(10);

    //     return view('dashboard.barang.index', compact('barangs'));
    // }

    public function index(Request $request)
    {
        $search = $request->search;

        $barangs = BarangAtk::when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('satuan', 'like', '%' . $search . '%');
            })
            ->withExists('detailPermintaan')
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString(); 

        return view('dashboard.barang.index', compact('barangs'));
    }

    public function search(Request $request)
{
    $q = $request->q;
    $sort = $request->get('sort', 'nama_barang');
    $direction = $request->get('direction', 'asc');

    // whitelist kolom yang boleh di-sort
    if (!in_array($sort, ['nama_barang', 'stok'])) {
        $sort = 'nama_barang';
    }

    if (!in_array($direction, ['asc', 'desc'])) {
        $direction = 'asc';
    }

    $barangs = BarangAtk::where('nama_barang', 'like', "%$q%")
        ->orderBy($sort, $direction)
        ->get(['id', 'nama_barang', 'satuan', 'stok']);

    return response()->json($barangs);
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

    
    public function riwayat(Request $request, $id)
    {
        $barang = BarangAtk::findOrFail($id);

        $query = MutasiStok::with('user')
            ->where('barang_id', $id)
            ->orderBy('tanggal', 'desc');

        // filter jenis mutasi
        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_mutasi', $request->jenis);
        }

        // filter bulan (format: YYYY-MM)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', Carbon::parse($request->bulan)->month)
                ->whereYear('tanggal', Carbon::parse($request->bulan)->year);
        }

        $mutasi = $query
        ->orderByDesc('updated_at')
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

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
