<?php

namespace App\Http\Controllers;

use App\Models\PermintaanAtk;
use App\Models\DetailPermintaanAtk;
use App\Models\BarangAtk;
use App\Models\MutasiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermintaanAtkController extends Controller
{
    public function index()
    {
        $permintaan = PermintaanAtk::with([
                'pegawai',
                'pencatat',
                'detail'
            ])
            ->orderBy('tanggal_permintaan', 'desc')
            ->paginate(20);

        return view('dashboard.permintaan.index', compact('permintaan'));
    }

    public function create()
    {
        return view('dashboard.permintaan.create', [
            'pegawais' => \App\Models\Pegawai::orderBy('nama')->get(),
            'barangs'  => BarangAtk::orderBy('nama_barang')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id'          => 'required|exists:pegawai,id',
            'tanggal_permintaan'  => 'required|date',
            'keperluan'           => 'required|string',
            'barang_id'           => 'required|array|min:1',
            'barang_id.*'         => 'exists:barang_atk,id',
            'jumlah'              => 'required|array',
            'jumlah.*'            => 'integer|min:1',
            'keterangan'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // HEADER
            $permintaan = PermintaanAtk::create([
                'pegawai_id'         => $request->pegawai_id,
                'tanggal_permintaan' => $request->tanggal_permintaan,
                'keperluan'          => $request->keperluan,
                'keterangan'         => $request->keterangan,
                'dicatat_oleh'       => Auth::id(),
            ]);

            // DETAIL + MUTASI
            foreach ($request->barang_id as $i => $barangId) {

                $barang = BarangAtk::lockForUpdate()->findOrFail($barangId);
                $jumlah = $request->jumlah[$i];

                if ($barang->stok < $jumlah) {
                    throw ValidationException::withMessages([
                        'stok' => "Stok {$barang->nama_barang} tidak mencukupi"
                    ]);
                }

                $stokAwal  = $barang->stok;
                $stokAkhir = $stokAwal - $jumlah;

                // update stok barang
                $barang->update([
                    'stok' => $stokAkhir
                ]);

                // detail permintaan
                DetailPermintaanAtk::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id'     => $barangId,
                    'jumlah'        => $jumlah,
                ]);

                // mutasi stok (keluar)
                MutasiStok::create([
                    'barang_id'    => $barangId,
                    'jenis_mutasi' => 'keluar',
                    'jumlah'       => $jumlah,
                    'stok_awal'    => $stokAwal,
                    'stok_akhir'   => $stokAkhir,
                    'tanggal'      => $request->tanggal_permintaan,
                    'keterangan'   => 'Permintaan ATK',
                    'user_id'      => Auth::id(),
                ]);
            }
        });

        return redirect()
            ->route('permintaan.index')
            ->with('success', 'Permintaan ATK berhasil dicatat');
    }

    public function show($id)
    {
        $permintaan = PermintaanAtk::with([
            'pegawai',
            'pencatat',
            'detail.barang'
        ])->findOrFail($id);

        return view('dashboard.permintaan.show', compact('permintaan'));
    }

}
