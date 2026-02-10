<?php

namespace App\Http\Controllers;

use App\Models\BarangAtk;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data barang dari database
        $barang = BarangAtk::orderBy('nama_barang')->get();

        // Bentuk data untuk chart
        $dataBarang = [];

        foreach ($barang as $item) {
            $dataBarang[$item->nama_barang] = [
                'ready' => $item->stok,
                'satuan' => $item->satuan,
            ];
        }

        return view('dashboard.main', compact('dataBarang'));
    }
}
