<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Divisi;
use App\Models\Host;
use App\Models\lokasi;
use App\Models\UndanganPengunjung;
use App\Models\UpModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class adminController extends Controller
{
    public function index()
    {
    $hosts = Host::all();
    $title = "Halaman host";
    return view('admin.hostadmin', compact('hosts', 'title'));
    }
    
    public function store(Request $request)
    {
    // Validasi input
    $request->validate([
        'nama' => 'required',
        'username' => 'required|unique:host',
        'password' => 'required|confirmed',
        'jenis_kelamin' => 'required',
        'nomor_telepon' => 'required',
        'email' => 'required|email|unique:host',
        'alamat' => 'required',
        'divisi_id' => 'required|exists:divisi,id', // Ubah divisi menjadi divisi_id dan pastikan divisi tersebut ada dalam tabel divisi
        'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto profil (opsional)
    ]);

    // Simpan foto profil jika diunggah
    if ($request->hasFile('foto_profil')) {
        $foto_profil = $request->file('foto_profil');
        $nama_foto = time().'.'.$foto_profil->getClientOriginalExtension();
        $lokasi_simpan = '/foto_profile/';
        $foto_profil->move(public_path($lokasi_simpan), $nama_foto);
        $lokasi_simpan .= $nama_foto;
    } else {
        $lokasi_simpan = null; // Atur lokasi_simpan menjadi null jika tidak ada file yang diunggah
    }

    // Simpan data host baru
    Host::create([
        'nama' => $request->nama,
        'username' => $request->username,
        'password' => bcrypt($request->password),
        'jenis_kelamin' => $request->jenis_kelamin,
        'nomor_telepon' => $request->nomor_telepon,
        'email' => $request->email,
        'alamat' => $request->alamat,
        'divisi_id' => $request->divisi_id,
        'lokasi_id' => $request->lokasi_id,
        'foto_profil' => $lokasi_simpan, // Simpan lokasi file gambar profil ke dalam database
    ]);

    // Redirect ke halaman yang sesuai
    session()->flash('success_tambahhost', true);
    return redirect()->route('tampilkan_host')->with('success_tambahhost', 'Host berhasil ditambahkan.');
    }


    public function detail($id)
    {
    $host = Host::find($id);
    if (!$host) {
        abort(404); // Jika host tidak ditemukan, tampilkan halaman error 404
    }
    return view('admin.detailhost', compact('host'));
    }


    // public function update(Request $request, $id){
    // $host = Host::find($id);
    // $host->update($request->all());
    // return redirect()->route('hostadmin.show')->with('success', 'Data host berhasil diperbarui.');
    // }

    public function destroy(Host $host)
    {
        if ($host) {
            $host->delete();

            session()->flash('success_hapushost', true);
            return redirect()->route('hostadmin.show')->with('success_hapushost', 'Data host berhasil dihapus.');
        }
        return redirect()->route('hostadmin.show')->with('error', 'Data host tidak ditemukan.');
    }

    public function index_tambah_host()
    {
        $divisis = Divisi::all();
        $lokasis = lokasi::all();
        return view('admin.tambahhost', compact('divisis', 'lokasis'));
    }

    public function index_riwayat()
    {
        
        // Mendapatkan data undangan dengan status "ditolak", "kadaluarsa", dan "selesai"
        // $undangan = UndanganPengunjung::whereIn('status', ['Ditolak', 'Kadaluarsa']) ->orWhere(function ($query) {$query->where('status', 'Selesai')->where('waktu_kembali', '<', now());})->get();
        
        $undangan = UndanganPengunjung::whereIn('status', ['Ditolak', 'Kadaluarsa', 'Selesai'])->get();
        // $umodel = UndanganPengunjung::all();
                        
        
        $title = "Riwayat";
        return view('admin.riwayat_admin', compact('undangan', 'title'));
    }    

    public function edit_host($id)
    {
        $host = Host::find($id);
        $divisions = Divisi::all();
        $locations = Lokasi::all();
        // Add any additional data needed for the edit form, e.g., divisions, locations, etc.
        return view('admin/edit_host', compact('host', 'divisions', 'locations'));
    }

    public function update_host(Request $request, $id)
    {
        $host = Host::find($id);

        // Update data dari bidang teks
        $host->nama = $request->input('nama');
        $host->username = $request->input('username');
        $host->alamat = $request->input('alamat');
        $host->nomor_telepon = $request->input('nomor_telepon');
        $host->email = $request->input('email');
        $host->jenis_kelamin = $request->input('jenis_kelamin');
        $host->divisi_id = $request->input('divisi');
        $divisiName = $request->input('divisi');
        $divisi = Divisi::where('nama_divisi', $divisiName)->first();
        if ($divisi) {
            $host->divisi_id = $divisi->id;
        } else {
            // Handle jika nama divisi tidak ditemukan
        }

        $host->lokasi_id = $request->input('lokasi');

        // Cek apakah ada file foto profil yang diunggah
        if ($request->hasFile('foto_profil')) {
            $foto_profil = $request->file('foto_profil');
            $nama_foto = time().'.'.$foto_profil->getClientOriginalExtension();
            $lokasi_simpan = '/foto_profile/';
            $foto_profil->move(public_path($lokasi_simpan), $nama_foto);
            $lokasi_simpan .= $nama_foto;

            // Simpan lokasi gambar dalam database
            $host->foto_profil = $lokasi_simpan;
        }

        // Simpan perubahan ke dalam database
        $host->save();

        return redirect()->route('tampilkan_host')->with('success_updatehost', 'Host berhasil diperbarui');
    }

    public function index_cetak(){

        return view('admin/cetak');
    }

    public function cetak()
    {
        $undangan = UndanganPengunjung::all();
        $pdf = PDF::loadView('admin/cetak', compact('undangan'));
        return $pdf->download('riwayat_undangan.pdf');
    }

    public function index_informasitamu() {
        $undangan = UndanganPengunjung::where('status', 'diterima')->with('divisi')->get();
        return view('admin.informasi_tamu', compact('undangan'));
    }

    public function index_profile_admin()
    {
        // Ambil admin yang sedang login
        $admin = Auth::guard('admin')->user();

        return view('admin.profile_admin', compact('admin'));
    }

    public function index_profile_entry(){

        $Entry = Auth::guard('entry_point')->user();

        return view('entry_point.profile_entrypoint', compact('Entry'));
    }

}
