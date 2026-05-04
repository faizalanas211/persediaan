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
    public function index(Request $request)
    {
        $search = $request->search;

        $barangs = BarangAtk::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('satuan', 'like', '%' . $search . '%');
                });
            })
            ->withExists('detailPermintaan')
            ->withCount('mutasiStok')
            ->with(['mutasiStok' => function ($q) {
                $q->select('id', 'barang_id', 'jenis_mutasi');
            }])
            ->orderBy('nama_barang')
            ->paginate(10)->onEachSide(2)
            ->withQueryString();

        return view('dashboard.barang.index', compact('barangs'));
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $sort = $request->get('sort', 'nama_barang');
        $direction = $request->get('direction', 'asc');

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
            'kode_barang'     => 'nullable|unique:barang_atk,kode_barang',
            'nama_barang'     => 'required|string|max:255',
            'satuan'          => 'required|string|max:50',
            'satuan_lainnya'  => 'required_if:satuan,lainnya|max:50',
            'stok'            => 'nullable|integer|min:0',
        ]);

        $satuan = $request->satuan === 'lainnya'
            ? $request->satuan_lainnya
            : $request->satuan;

        DB::transaction(function () use ($request, $satuan) {

            $jumlah = $request->stok ?? 0;

            // ================= AUTO GENERATE KODE =================
            $kodeBarang = $request->kode_barang;

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

            // Simpan barang dengan stok awal (0 atau sesuai input)
            $barang = BarangAtk::create([
                'kode_barang' => $kodeBarang,
                'nama_barang' => $request->nama_barang,
                'satuan'      => $satuan,
                'stok'        => $jumlah,
            ]);

            // Hanya buat mutasi jika stok awal > 0
            if ($jumlah > 0) {
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'masuk',
                    'jumlah'       => $jumlah,
                    'stok_awal'    => 0,
                    'stok_akhir'   => $jumlah,
                    'tanggal'      => now(),
                    'keterangan'   => 'Stok awal barang',
                    'user_id'      => Auth::id(),
                ]);
            }
        });

        $message = $request->stok > 0 
            ? 'Barang berhasil ditambahkan dan stok awal tercatat di mutasi.'
            : 'Barang berhasil ditambahkan dengan stok 0 (tidak tercatat di mutasi).';

        return redirect()
            ->route('barang.index')
            ->with('success', $message);
    }

    public function edit(BarangAtk $barang)
    {
        return view('dashboard.barang.edit', compact('barang'));
    }

    public function update(Request $request, BarangAtk $barang)
    {
        $request->validate([
            'kode_barang'     => 'nullable|unique:barang_atk,kode_barang,' . $barang->id,
            'nama_barang'     => 'required|string|max:255',
            'satuan'          => 'required|string|max:50',
            'satuan_lainnya'  => 'required_if:satuan,lainnya|max:50',
            'stok'            => 'nullable|integer|min:0',
        ]);

        $satuan = $request->satuan === 'lainnya'
            ? $request->satuan_lainnya
            : $request->satuan;

        $stokBaru = $request->stok;
        $stokLama = $barang->stok;

        DB::transaction(function () use ($request, $satuan, $barang, $stokBaru, $stokLama) {
            // Update data barang
            $barang->update([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'satuan'      => $satuan,
            ]);

            // Jika stok diubah dan stok baru > stok lama, buat mutasi MASUK
            if ($stokBaru !== null && $stokBaru > $stokLama) {
                $selisih = $stokBaru - $stokLama;
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'masuk',
                    'jumlah'       => $selisih,
                    'stok_awal'    => $stokLama,
                    'stok_akhir'   => $stokBaru,
                    'tanggal'      => now(),
                    'keterangan'   => 'Penyesuaian stok via edit barang',
                    'user_id'      => Auth::id(),
                ]);

                $barang->update(['stok' => $stokBaru]);
            }
            // Jika stok baru < stok lama, buat mutasi KELUAR
            elseif ($stokBaru !== null && $stokBaru < $stokLama) {
                $selisih = $stokLama - $stokBaru;
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'keluar',
                    'jumlah'       => $selisih,
                    'stok_awal'    => $stokLama,
                    'stok_akhir'   => $stokBaru,
                    'tanggal'      => now(),
                    'keterangan'   => 'Penyesuaian stok via edit barang',
                    'user_id'      => Auth::id(),
                ]);

                $barang->update(['stok' => $stokBaru]);
            }
        });

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

        DB::transaction(function () use ($barang) {
            $barang->mutasiStok()->delete();
            $barang->delete();
        });

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang dan seluruh riwayat mutasinya berhasil dihapus!');
    }
    
    public function riwayat(Request $request, $id)
    {
        $barang = BarangAtk::findOrFail($id);

        $query = MutasiStok::with('user')
            ->where('barang_id', $id)
            ->orderBy('tanggal', 'desc');

        if ($request->filled('jenis') && $request->jenis !== 'all') {
            $query->where('jenis_mutasi', $request->jenis);
        }

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
            // Import dengan logika:
            // - Stok > 0 → buat mutasi MASUK
            // - Stok = 0 → simpan barang tanpa mutasi
            // Logika ini DIATUR di file App\Imports\BarangAtkImport
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