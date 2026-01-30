<?php

namespace App\Http\Controllers;

use App\Imports\StokOpnameImport;
use App\Models\BarangAtk;
use App\Models\DetailStokOpname;
use App\Models\MutasiStok;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::with([
            'pencatat',
            'detail' // untuk hitung jumlah barang
        ]);

        // FILTER PERIODE (format: YYYY-MM)
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
        $barangs = BarangAtk::orderBy('nama_barang')->get();

        return view('dashboard.stok_opname.create', [
            'barangs' => $barangs,
            'periode' => now()->startOfMonth()->toDateString(),
            'tanggal' => now()->toDateString(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan'   => 'required|date',
            'tanggal_opname'  => 'required|date',
            'barang_id'       => 'required|array',
            'stok_fisik'      => 'required|array',
        ]);

        DB::transaction(function () use ($request) {

            // HEADER
            $stokOpname = StokOpname::create([
                'periode_bulan'  => $request->periode_bulan,
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
                'user_id'        => Auth::id(),
                'status'         => 'draft',
            ]);

            // DETAIL
            foreach ($request->barang_id as $i => $barangId) {

                $stokSistem = BarangAtk::find($barangId)->stok;
                $stokFisik  = $request->stok_fisik[$i];
                $selisih    = $stokFisik - $stokSistem;

                $stokOpname->detail()->create([
                    'barang_id'   => $barangId,
                    'stok_sistem' => $stokSistem,
                    'stok_fisik'  => $stokFisik,
                    'selisih'     => $selisih,
                    'keterangan'  => $request->keterangan_detail[$i] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('stok-opname.index')
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

            // HEADER
            $stokOpname = StokOpname::findOrFail($id);
            $stokOpname->update([
                'periode_bulan'  => $request->periode_bulan,
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
            ]);

            // DETAIL
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

        return redirect()
            ->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil diperbarui');
    }

    public function show($id) { 
        $stokOpname = StokOpname::with([ 'pencatat', 'detail.barang' ])->findOrFail($id); 
        
        return view('dashboard.stok_opname.show', compact('stokOpname')); 
    }

    public function final($id)
    {
        $stokOpname = StokOpname::with('detail.barang')->findOrFail($id);

        if ($stokOpname->status === 'final') {
            return back()->with('error', 'Stok opname ini sudah difinalkan.');
        }

        DB::transaction(function () use ($stokOpname) {

            $periode = Carbon::parse($stokOpname->periode_bulan);

            foreach ($stokOpname->detail as $detail) {

                $barang = $detail->barang;

                // Hitung total mutasi periode opname
                $mutasi = MutasiStok::where('barang_id', $barang->id)
                    ->whereMonth('tanggal', $periode->month)
                    ->whereYear('tanggal', $periode->year)
                    ->selectRaw("
                        SUM(CASE WHEN jenis_mutasi = 'masuk' THEN jumlah ELSE 0 END) as total_masuk,
                        SUM(CASE WHEN jenis_mutasi = 'keluar' THEN jumlah ELSE 0 END) as total_keluar
                    ")
                    ->first();

                // Simpan ke detail opname (snapshot)
                $detail->update([
                    'total_masuk'  => $mutasi->total_masuk ?? 0,
                    'total_keluar' => $mutasi->total_keluar ?? 0,
                ]);

                // Lewati kalau tidak ada selisih
                if ($detail->selisih == 0) {
                    continue;
                }

                $stokAwal  = $barang->stok;
                $stokAkhir = $detail->stok_fisik;

                // Update stok barang
                $barang->update([
                    'stok' => $stokAkhir
                ]);

                // Mutasi penyesuaian
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'penyesuaian',
                    'jumlah'       => abs($detail->selisih),
                    'stok_awal'    => $stokAwal,
                    'stok_akhir'   => $stokAkhir,
                    'tanggal'      => Carbon::now(),
                    'keterangan'   => 'Stok opname periode ' .
                        $periode->format('F Y') .
                        ($detail->keterangan ? ' - '.$detail->keterangan : ''),
                    'user_id'      => Auth::id(),
                ]);
            }

            $stokOpname->update([
                'status' => 'final'
            ]);
        });

        return redirect()
            ->route('stok-opname.show', $stokOpname->id)
            ->with('success', 'Stok opname berhasil difinalkan dan stok diperbarui!');
    }
    public function exportPdf($id)
    {
        $stokOpname = StokOpname::with([
            'detail.barang',
            'pencatat'
        ])->findOrFail($id);

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
            \Carbon\Carbon::parse($stokOpname->periode_bulan)->format('Y-m') .
            '.pdf'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'periode_bulan'  => 'required|date',
            'tanggal_opname' => 'required|date',
            'file'           => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // HEADER STOK OPNAME
            $stokOpname = StokOpname::create([
                'periode_bulan'  => $request->periode_bulan,
                'tanggal_opname' => $request->tanggal_opname,
                'keterangan'     => $request->keterangan,
                'user_id'        => Auth::id(),
                'status'         => 'draft',
            ]);

            // IMPORT DETAIL
            Excel::import(
                new StokOpnameImport($stokOpname),
                $request->file('file')
            );

            DB::commit();

            return redirect()
                ->route('stok-opname.index')
                ->with('success', 'Stok opname berhasil diimpor dari Excel!');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors([
                'file' => $e->getMessage()
            ]);
        }
    }
}
