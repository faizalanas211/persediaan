<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Halaman profil
     */
    public function show()
    {
        return view('auth.profil');
    }

    public function edit()
    {
        $user = Auth::user();

        return view('auth.profil_edit', compact('user'));
    }

    /**
     * Update data profil
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'jenis_kelamin'    => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir'     => 'nullable|string|max:100',
            'tanggal_lahir'    => 'nullable|date',
            'jabatan'          => 'nullable|string|max:100',
            'pangkat_golongan' => 'nullable|string|max:100',
            'foto'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        /* ================= UPDATE USERS ================= */
        $user->update([
            'name' => $request->name,
        ]);

        /* ================= DATA PEGAWAI ================= */
        $pegawaiData = [
            'nama'             => $request->name, // 🔥 ambil dari field yg sama
            'jenis_kelamin'    => $request->jenis_kelamin,
            'tempat_lahir'     => $request->tempat_lahir,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'jabatan'          => $request->jabatan,
            'pangkat_golongan' => $request->pangkat_golongan,
        ];

        /* ================= UPLOAD FOTO ================= */
        if ($request->hasFile('foto')) {
            $pegawaiData['foto'] = $request->file('foto')
                ->store('pegawai', 'public');
        }

        /* ================= UPDATE / CREATE PEGAWAI ================= */
        if ($user->pegawai) {
            $user->pegawai->update($pegawaiData);
        } else {
            $user->pegawai()->create($pegawaiData);
        }

        return redirect()
            ->route('profil.show', auth()->user()->id)
            ->with('success', 'Profil berhasil diperbarui.');

    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
