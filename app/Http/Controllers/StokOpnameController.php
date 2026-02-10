<?php

namespace App\Http\Controllers;

use App\Models\BarangAtk;
use App\Models\DetailStokOpname;
use App\Models\MutasiStok;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/* PDF & Excel */
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokOpnameExport;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::with(['pencatat', 'detail']);

        if ($request->filled('periode')) {
            $query->whereMonth('periode_bulan', substr($request->periode, 5, 2))
                  ->whereYear('periode_bulan', substr($request->periode, 0, 4));
        }

        $stokOpnames = $query
            ->orderBy('periode_bulan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.stok_opname.index', compact('stokOpnames'));
    }

    public function create()
    {
        return view('dashboard.stok_opname.create', [
            'barangs' => BarangAtk::orderBy('nama_barang')->get(),
            'periode' => now()->startOfMonth()->toDateString(),
            'tanggal' => now()->toDateString(),
        ]);
    }

    /* ======================= STORE ======================= */
    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan'  => 'required|date',
            'tanggal_opname' => 'required|date',
            'barang_id'      => 'required|array',
            'stok_fisik'     => 'required|array',
        ]);

        $periode = Carbon::parse($request->periode_bulan);

        /* ===== CEK DULU SEBELUM TRANSACTION ===== */
        if (StokOpname::whereYear('periode_bulan', $periode->year)
            ->whereMonth('periode_bulan', $periode->month)
            ->exists()) {

            return back()
                ->withInput()
                ->with('warning', 'Stok opname periode ini sudah ada.');
        }

        DB::transaction(function () use ($request, $periode) {

            $stokOpname = StokOpname::create([
                'periode_bulan'  => $periode->startOfMonth(),
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
                'user_id'        => Auth::id(),
                'status'         => 'draft',
            ]);

            foreach ($request->barang_id as $i => $barangId) {

                $barang     = BarangAtk::findOrFail($barangId);
                $stokSistem = $barang->stok;
                $stokFisik  = $request->stok_fisik[$i];
                $selisih    = $stokFisik - $stokSistem;

                $totalMasuk = MutasiStok::where('barang_id', $barangId)
                    ->where('jenis_mutasi', 'masuk')
                    ->whereMonth('tanggal', $periode->month)
                    ->whereYear('tanggal', $periode->year)
                    ->sum('jumlah');

                $totalKeluar = MutasiStok::where('barang_id', $barangId)
                    ->where('jenis_mutasi', 'keluar')
                    ->whereMonth('tanggal', $periode->month)
                    ->whereYear('tanggal', $periode->year)
                    ->sum('jumlah');

                $stokOpname->detail()->create([
                    'barang_id'    => $barangId,
                    'stok_sistem'  => $stokSistem,
                    'stok_fisik'   => $stokFisik,
                    'selisih'      => $selisih,
                    'total_masuk'  => $totalMasuk,
                    'total_keluar' => $totalKeluar,
                    'keterangan'   => $request->keterangan_detail[$i] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil dibuat!');
    }

    /* sisanya tetap sama */
}
