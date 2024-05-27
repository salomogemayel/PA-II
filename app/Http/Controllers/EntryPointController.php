<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use App\Models\UndanganPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EntryPointController extends Controller
{
    // public function index_entrypoint()
    // {
    //     $title = "Beranda";
    //     return view('entry_point.berandaentrypoint', compact('title'));
    // }

    public function index_entrypoint(){
        // Semua kunjungan
        $semua_kunjungan = UndanganPengunjung::all()->count();

        $recentUsers = Pengunjung::orderBy('last_login', 'desc')->take(5)->get();
    
        // Kunjungan yang akan datang hari ini (status = diterima dan waktu_temu = hari ini)
        $kunjungan_hari_ini = UndanganPengunjung::where('status', 'diterima')
            ->whereDate('waktu_temu', Carbon::today())
            ->count();
    
        // Kunjungan yang check in/check out (memiliki nilai pada atribut check_in atau check_out)
        $kunjungan_check_in_out = UndanganPengunjung::whereNotNull('check_in')->orWhereNotNull('check_out')->count();
    
        // Kunjungan yang kadaluarsa (status = kadaluarsa)
        $kunjungan_kadaluarsa = UndanganPengunjung::where('status', 'kadaluarsa')->count();
    
        return view('entry_point.berandaentrypoint', compact('semua_kunjungan', 'kunjungan_hari_ini','kunjungan_check_in_out', 'kunjungan_kadaluarsa' ,'recentUsers'));
    }
    
    public function scanQrCode(Request $request)
    {
        try {
            $details = json_decode($request->getContent(), true);
    
            if (!$details || !isset($details['ID'])) {
                return response()->json(['error' => 'Invalid QR code data'], 400);
            }
    
            // Temukan undangan berdasarkan ID
            $undangan = UndanganPengunjung::find($details['ID']);
    
            if (!$undangan) {
                return response()->json(['error' => 'Undangan tidak ditemukan'], 404);
            }
    
            // Memeriksa kesesuaian data pada QR code dengan data di database
            if ($undangan->nama != $details['Nama'] || $undangan->waktu_temu != $details['Tanggal'] || $undangan->ruangan != $details['Lokasi']) {
                return response()->json(['error' => 'Data pada QR code tidak sesuai dengan data undangan'], 400);
            }
    
            // Memeriksa status check-in dan check-out
            if ($undangan->check_out) {
                return response()->json(['error' => 'Anda sudah check-out sebelumnya.'], 400);
            }
    
            if (!$undangan->check_in) {
                // Jika belum check-in, tandai sebagai check-in
                $undangan->update(['check_in' => true]);
            } else {
                // Jika sudah check-in, tandai sebagai check-out
                $undangan->update(['check_out' => true]);
                return response()->json(['message' => 'Check-out berhasil.'], 200);
            }
    
            // Kembalikan detail undangan
            return response()->json([
                'ID' => $undangan->id,
                'Nama' => $undangan->pengunjung->namaLengkap,
                'Tanggal' => $undangan->waktu_temu,
                'Lokasi' => $undangan->ruangan,
                'Pesan' => $undangan->keterangan,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memproses QR code'], 500);
        }
    }
    

    public function index_scanQrCode(){
        return view('entry_point/checkin_out');
    }

    public function index_riwayat(){
        $undangan = UndanganPengunjung::whereIn('status', ['Ditolak', 'Kadaluarsa', 'Selesai'])->get();
        return view('entry_point/riwayatEP', compact('undangan'));
    }

    public function index_IT(){
        $undangan = UndanganPengunjung::where('status', 'Diterima')->with('divisi')->get();
        return view('entry_point/informasitamuEP', compact('undangan'));
    }
    
}
