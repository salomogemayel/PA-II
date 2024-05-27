<?php

namespace App\Http\Controllers;

use App\Models\Host;
use App\Models\UndanganPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pengunjungController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('edit_profile', compact('user'));
    }

    public function update(Request $request)
{
    $user = Auth::user();
    
    // Validasi bahwa pengguna hanya dapat mengedit profil mereka sendiri
    if ($request->user()->id !== $user->id) {
        abort(403); // Unauthorized
    }

    // Perbarui profil pengguna
    $user = \App\Models\User::find($user->id); // Mengonversi menjadi instance model User yang sebenarnya
    $user->namaLengkap = $request->namaLengkap;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->jenis_kelamin = $request->jenis_kelamin;
    $user->nomor_telepon = $request->nomor_telepon;
    $user->alamat = $request->alamat;
    

    if ($request->hasFile('foto_profil')) {
        $image = $request->file('foto_profil');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('foto_profile_user_update'), $imageName);
        $user->foto_profil = 'foto_profile_user_update/' . $imageName;
    }

    $user->save();

    return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
}

    public function index_pantau(){
        $host_id = Auth::id();

        $undangans = UndanganPengunjung::where('host_id', $host_id)->get();
        return view('pantau_kunjungan', compact('undangans'));
    }

    public function index_riwayat_pengunjung(){
        $pengunjung_id = Auth::id();
    
        $undangan = UndanganPengunjung::where('pengunjung_id', $pengunjung_id)->whereIn('status', ['Ditolak', 'Kadaluarsa', 'Selesai'])->get();
        return view('riwayat', compact('undangan'));
    }
    
    
}
