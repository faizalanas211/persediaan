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
                'pencatat',
                'detail'
            ])
            ->orderBy('tanggal_permintaan', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.permintaan.index', compact('permintaan'));
    }

    public function create()
    {
        return view('dashboard.permintaan.create', [
            'barangs'  => BarangAtk::orderBy('nama_barang')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemohon'        => 'required|string|max:255',
            'nip_pemohon'         => 'nullable|string|max:50',
            'bagian_pemohon'      => 'nullable|string|max:100',

            'tanggal_permintaan'  => 'required|date',
            'keperluan'           => 'required|string',
            'barang_id'           => 'required|array|min:1',
            'barang_id.*'         => 'exists:barang_atk,id',
            'jumlah'              => 'required|array',
            'jumlah.*'            => 'integer|min:1',
            'keterangan'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // HEADER (DRAFT)
            $permintaan = PermintaanAtk::create([
                'nama_pemohon'        => $request->nama_pemohon,
                'nip_pemohon'         => $request->nip_pemohon,
                'bagian_pemohon'      => $request->bagian_pemohon,

                'tanggal_permintaan' => $request->tanggal_permintaan,
                'keperluan'          => $request->keperluan,
                'keterangan'         => $request->keterangan,
                'dicatat_oleh'       => Auth::id(),
                'status'             => 'draft',
            ]);

            // Hanya menyimpan detail permintaan
            foreach ($request->barang_id as $i => $barangId) {
                DetailPermintaanAtk::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id'     => $barangId,
                    'jumlah'        => $request->jumlah[$i],
                ]);
            }
        });

        return redirect()
            ->route('permintaan.index')
            ->with('success', 'Permintaan ATK berhasil disimpan sebagai draft!');
    }

    public function proses(PermintaanAtk $permintaan)
    {
        // pastikan masih draft
        if ($permintaan->status !== 'draft') {
            return back()->with('error', 'Permintaan sudah diproses');
        }

        DB::transaction(function () use ($permintaan) {

            foreach ($permintaan->detail as $detail) {

                $barang = BarangAtk::lockForUpdate()
                            ->findOrFail($detail->barang_id);

                $jumlah = $detail->jumlah;

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

                // mutasi stok
                MutasiStok::create([
                    'barang_id'    => $barang->id,
                    'jenis_mutasi' => 'keluar',
                    'jumlah'       => $jumlah,
                    'stok_awal'    => $stokAwal,
                    'stok_akhir'   => $stokAkhir,
                    'tanggal'      => now(),
                    'keterangan'   => 'Permintaan ATK #' . $permintaan->id,
                    'user_id'      => Auth::id(),
                ]);
            }

            // update status permintaan
            $permintaan->update([
                'status' => 'diproses'
            ]);
        });

        return redirect()
            ->route('permintaan.index', $permintaan->id)
            ->with('success', 'Permintaan berhasil diproses');
    }

    public function show($id)
    {
        $permintaan = PermintaanAtk::with([
            'pencatat',
            'detail.barang'
        ])->findOrFail($id);

        return view('dashboard.permintaan.show', compact('permintaan'));
    }

    public function edit(PermintaanAtk $permintaan)
    {
        if ($permintaan->status !== 'draft') {
            return redirect()
                ->route('permintaan.show', $permintaan->id)
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diedit');
        }

        $barangs  = BarangAtk::orderBy('nama_barang')->get();

        $permintaan->load('detail.barang');

        return view('dashboard.permintaan.edit', compact(
            'permintaan',
            'barangs'
        ));
    }

    public function update(Request $request, PermintaanAtk $permintaan)
    {
        if ($permintaan->status !== 'draft') {
            return back()->with('error', 'Permintaan tidak dapat diubah');
        }

        $request->validate([
            'nama_pemohon'   => 'required|string|max:100',
            'nip_pemohon'    => 'required|string|max:50',
            'bagian_pemohon' => 'required|string|max:100',

            'tanggal_permintaan' => 'required|date',
            'keperluan'          => 'required|string',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'exists:barang_atk,id',
            'jumlah'             => 'required|array',
            'jumlah.*'           => 'integer|min:1',
            'keterangan'         => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $permintaan) {

            // update header
            $permintaan->update([
                'nama_pemohon'   => $request->nama_pemohon,
                'nip_pemohon'    => $request->nip_pemohon,
                'bagian_pemohon' => $request->bagian_pemohon,

                'tanggal_permintaan' => $request->tanggal_permintaan,
                'keperluan'          => $request->keperluan,
                'keterangan'         => $request->keterangan,
            ]);

            // hapus detail lama
            $permintaan->detail()->delete();

            // simpan detail baru
            foreach ($request->barang_id as $i => $barangId) {
                DetailPermintaanAtk::create([
                    'permintaan_id' => $permintaan->id,
                    'barang_id'     => $barangId,
                    'jumlah'        => $request->jumlah[$i],
                ]);
            }
        });

        return redirect()
            ->route('permintaan.show', $permintaan->id)
            ->with('success', 'Permintaan berhasil diperbarui');
    }

    public function destroy(PermintaanAtk $permintaan)
    {
        if ($permintaan->status !== 'draft') {
            return back()->with('error', 'Permintaan tidak dapat dibatalkan');
        }

        $permintaan->update([
            'status' => 'dibatalkan'
        ]);

        return redirect()
            ->route('permintaan.index')
            ->with('success', 'Permintaan ATK berhasil dibatalkan');
    }

}
