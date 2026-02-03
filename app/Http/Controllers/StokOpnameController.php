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

/* PDF */
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan'  => 'required|date',
            'tanggal_opname' => 'required|date',
            'barang_id'      => 'required|array',
            'stok_fisik'     => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            $stokOpname = StokOpname::create([
                'periode_bulan'  => $request->periode_bulan,
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
                'user_id'        => Auth::id(),
                'status'         => 'draft',
            ]);

            // DETAIL
            foreach ($request->barang_id as $i => $barangId) {

                $stokSistem = BarangAtk::findOrFail($barangId)->stok;
                $stokFisik  = $request->stok_fisik[$i];
                $selisih    = $stokFisik - $stokSistem;

                // HITUNG TOTAL MASUK
                $totalMasuk = MutasiStok::where('barang_id', $barangId)
                    ->where('jenis_mutasi', 'masuk')
                    ->whereMonth('tanggal', $periode->month)
                    ->whereYear('tanggal', $periode->year)
                    ->sum('jumlah');

                // HITUNG TOTAL KELUAR
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

        return redirect()->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil dibuat!');
    }

    public function edit($id)
    {
        $stokOpname = StokOpname::findOrFail($id);
        $details    = $stokOpname->detail()->with('barang')->get();

        return view('dashboard.stok_opname.edit', compact('stokOpname', 'details'));
    }

    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $stokOpname = StokOpname::findOrFail($id);
            $stokOpname->update([
                'periode_bulan'  => $request->periode_bulan,
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
            ]);

            foreach ($request->detail_id as $i => $detailId) {

                $detail = DetailStokOpname::findOrFail($detailId);
                $stokFisik = $request->stok_fisik[$i];
                $selisih   = $stokFisik - $detail->stok_sistem;

                $detail->update([
                    'stok_fisik' => $stokFisik,
                    'selisih'    => $selisih,
                    'keterangan' => $request->keterangan_detail[$i] ?? null,
                ]);
            }
        });

        return redirect()->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil diperbarui');
    }

    public function show($id)
    {
        $stokOpname = StokOpname::with(['pencatat', 'detail.barang'])->findOrFail($id);
        return view('dashboard.stok_opname.show', compact('stokOpname'));
    }

    public function final($id)
    {
        $stokOpname = StokOpname::with('detail.barang')->findOrFail($id);

        if ($stokOpname->status === 'final') {
            return back()->with('error', 'Stok opname ini sudah difinalkan.');
        }

        DB::transaction(function () use ($stokOpname) {

            foreach ($stokOpname->detail as $detail) {

                if ($detail->selisih == 0) continue;

                $barang    = $detail->barang;
                $stokAwal  = $barang->stok;
                $stokAkhir = $detail->stok_fisik;

                $barang->update(['stok' => $stokAkhir]);

                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'penyesuaian',
                    'jumlah'       => abs($detail->selisih),
                    'stok_awal'    => $stokAwal,
                    'stok_akhir'   => $stokAkhir,
                    'tanggal'      => Carbon::now(),
                    'keterangan'   => 'Stok opname periode ' .
                        Carbon::parse($stokOpname->periode_bulan)->translatedFormat('F Y'),
                    'user_id'      => Auth::id(),
                ]);
            }

            $stokOpname->update(['status' => 'final']);
        });

        return redirect()->route('stok-opname.show', $stokOpname->id)
            ->with('success', 'Stok opname berhasil difinalkan!');
    }
    public function exportPdf($id)
    {
        $stokOpname = StokOpname::with(['detail.barang', 'pencatat'])->findOrFail($id);

        // proteksi
        if ($stokOpname->status !== 'final') {
            abort(403, 'Stok opname belum difinalisasi');
        }

        $pdf = Pdf::loadView(
            'dashboard.stok_opname.export-pdf',
            compact('stokOpname')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'stok-opname-' .
            Carbon::parse($stokOpname->periode_bulan)->format('Y-m') .
            '.pdf'
        );
    }

    /* ======================= EXPORT EXCEL ======================= */
    public function exportExcel($id)
    {
        $stokOpname = StokOpname::with(['detail.barang', 'pencatat'])->findOrFail($id);

        if ($stokOpname->status !== 'final') {
            abort(403, 'Stok opname belum difinalisasi');
        }

        return Excel::download(
            new StokOpnameExport($stokOpname),
            'stok-opname-' .
            Carbon::parse($stokOpname->periode_bulan)->format('Y-m') .
            '.xlsx'
        );
    }
}
