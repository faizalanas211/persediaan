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
use Illuminate\Validation\ValidationException;

/* PDF & Excel */
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokOpnameExport;
use App\Exports\TemplateStokOpnameExport;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StokOpname::with(['pencatat','detail']);

        if ($request->filled('periode')) {
            $query->whereMonth('periode_bulan', substr($request->periode,5,2))
                  ->whereYear('periode_bulan', substr($request->periode,0,4));
        }

        $stokOpnames = $query->orderBy('periode_bulan','desc')
            ->orderBy('created_at','desc')
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.stok_opname.index', compact('stokOpnames'));
    }

    public function create()
    {
        return view('dashboard.stok_opname.create',[
            'barangs'=>BarangAtk::orderBy('nama_barang')->get(),
            'periode'=>now()->startOfMonth()->toDateString(),
            'tanggal'=>now()->toDateString(),
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new TemplateStokOpnameExport,
            'template-stok-opname.xlsx'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan'=>'required|date',
            'tanggal_opname'=>'required|date',
            'barang_id'=>'required|array',
            'stok_fisik'=>'required|array',
            'stok_fisik.*'=>'integer|min:0',
        ]);

        $periode = Carbon::parse($request->periode_bulan);

        if(StokOpname::whereYear('periode_bulan',$periode->year)
            ->whereMonth('periode_bulan',$periode->month)
            ->where('status','final')
            ->exists()){
            return back()->withInput()->with('warning','Periode sudah memiliki stok opname final');
        }

        DB::transaction(function() use ($request,$periode){

            $stokOpname = StokOpname::create([
                'periode_bulan'=>$periode->startOfMonth(),
                'tanggal_opname'=>$request->tanggal_opname,
                'keterangan'=>$request->keterangan,
                'user_id'=>Auth::id(),
                'status'=>'draft'
            ]);

            foreach($request->barang_id as $i=>$barangId){

                $barang = BarangAtk::findOrFail($barangId);

                $stokFisik = $request->stok_fisik[$i] ?? $barang->stok;

                $stokOpname->detail()->create([
                    'barang_id'=>$barangId,
                    'stok_sistem'=>$barang->stok,
                    'stok_fisik'=>$stokFisik,
                    'selisih'=>$stokFisik - $barang->stok,
                    'keterangan'=>$request->keterangan_detail[$i] ?? null
                ]);
            }
        });

        return redirect()->route('stok-opname.index')
            ->with('success','Stok opname berhasil dibuat');
    }

    public function show($id)
    {
        $stokOpname = StokOpname::with(['pencatat','detail.barang'])->findOrFail($id);
        return view('dashboard.stok_opname.show',compact('stokOpname'));
    }

    /**
     * ✅ REVISI: Finalize stok opname (hanya jika status draft)
     */
    public function final($id)
    {
        $stokOpname = StokOpname::with('detail.barang')->findOrFail($id);
        
        // Cek apakah sudah final
        if ($stokOpname->status === 'final') {
            return redirect()->back()->with('warning', 'Stok opname sudah difinalkan');
        }

        DB::transaction(function() use ($stokOpname){

            foreach($stokOpname->detail as $detail){

                if($detail->selisih==0) continue;

                $barang=$detail->barang;
                $stokAwal=$barang->stok;
                $stokAkhir=$detail->stok_fisik;

                // Pastikan stok tidak negatif
                if ($stokAkhir < 0) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak boleh negatif");
                }

                $barang->update(['stok'=>$stokAkhir]);

                MutasiStok::create([
                    'barang_id'=>$barang->id,
                    'jenis_mutasi'=>'penyesuaian',
                    'jumlah'=>abs($detail->selisih),
                    'stok_awal'=>$stokAwal,
                    'stok_akhir'=>$stokAkhir,
                    'tanggal'=>Carbon::now(),
                    'keterangan'=>'Penyesuaian stok opname periode ' . Carbon::parse($stokOpname->periode_bulan)->format('F Y'),
                    'user_id'=>Auth::id()
                ]);
            }

            $stokOpname->update(['status'=>'final']);
        });

        return redirect()->route('stok-opname.show',$stokOpname->id)
            ->with('success','Stok opname difinalkan');
    }

    /**
     * ✅ TAMBAHAN: Hapus stok opname (hanya untuk status draft)
     */
    public function destroy($id)
    {
        $stokOpname = StokOpname::with('detail')->findOrFail($id);
        
        // Hanya bisa hapus jika status draft
        if ($stokOpname->status !== 'draft') {
            return redirect()->back()->with('error', 'Hanya stok opname dengan status Draft yang bisa dihapus');
        }

        DB::transaction(function () use ($stokOpname) {
            // Hapus detail stok opname
            $stokOpname->detail()->delete();
            // Hapus stok opname
            $stokOpname->delete();
        });

        return redirect()->route('stok-opname.index')
            ->with('success', 'Stok opname berhasil dihapus');
    }

    public function exportPdf($id)
    {
        $stokOpname = StokOpname::with(['detail.barang','pencatat'])->findOrFail($id);

        $pdf = Pdf::loadView('dashboard.stok_opname.export-pdf',compact('stokOpname'))
            ->setPaper('A4','portrait');

        return $pdf->download('stok-opname-'.$stokOpname->id.'.pdf');
    }

    public function exportExcel($id)
    {
        $stokOpname = StokOpname::findOrFail($id);

        return Excel::download(
            new StokOpnameExport($stokOpname),
            'stok-opname-'.$stokOpname->id.'.xlsx'
        );
    }

    /**
     * ✅ REVISI: Import Excel dengan periode_bulan dari request
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
            'periode_bulan' => 'required|date',
        ]);

        $rows = Excel::toArray([], $request->file('file'))[0];

        $periode = Carbon::parse($request->periode_bulan)->startOfMonth();

        // Cek apakah periode sudah ada (hanya yang final)
        if (StokOpname::whereYear('periode_bulan', $periode->year)
            ->whereMonth('periode_bulan', $periode->month)
            ->where('status', 'final')
            ->exists()) {

            return back()
                ->withInput()
                ->with('warning', 'Periode sudah memiliki stok opname final');
        }

        DB::transaction(function() use ($rows, $periode, $request) {

            $stokOpname = StokOpname::create([
                'periode_bulan' => $periode,
                'tanggal_opname' => $request->tanggal_opname ?? Carbon::today(),
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
                'status' => 'draft'
            ]);

            foreach($rows as $i => $row){
                // Skip header
                if($i == 0) continue;
                
                // Skip jika nama_barang kosong
                if(empty($row[0])) continue;
                
                $barang = BarangAtk::where('nama_barang', $row[0])->first();
                if(!$barang) continue;
                
                $stokFisik = (int)($row[1] ?? 0);
                
                // Pastikan stok fisik tidak negatif
                if ($stokFisik < 0) $stokFisik = 0;

                $stokOpname->detail()->create([
                    'barang_id' => $barang->id,
                    'stok_sistem' => $barang->stok,
                    'stok_fisik' => $stokFisik,
                    'selisih' => $stokFisik - $barang->stok,
                    'keterangan' => $row[2] ?? null
                ]);
            }

        });

        return redirect()->route('stok-opname.index')
            ->with('success', 'Import stok opname berhasil');
    }
}