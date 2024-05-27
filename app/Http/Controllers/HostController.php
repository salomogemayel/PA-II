<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use Illuminate\Http\Request;
use App\Models\Host;
use App\Models\lokasi;
use App\Models\Pengunjung;
use App\Models\UndanganPengunjung;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HostController extends Controller
{
    public function index_host(){
        // Mendapatkan ID host yang sedang login
        $host_id = auth('host')->id();
    
        // Mendapatkan jumlah undangan masuk
        $undangan_masuk = UndanganPengunjung::where('host_id', $host_id)
            ->where('status', 'Menunggu')
            ->count();
    
        // Mendapatkan jumlah undangan yang akan datang
        $undangan_akan_datang = UndanganPengunjung::where('host_id', $host_id)
            ->where('status', 'Diterima')
            ->count();
    
        // Mendapatkan jumlah undangan yang sudah check in atau check out
        $undangan_check_in_out = UndanganPengunjung::where('host_id', $host_id)
            ->whereNotNull('check_in')
            ->orWhereNotNull('check_out')
            ->count();
    
        // Mendapatkan jumlah undangan yang kadaluarsa
        $undangan_kadaluarsa = UndanganPengunjung::where('host_id', $host_id)
            ->where('status', 'Kadaluarsa')
            ->count();
    
        return view('host/berandahost', compact('undangan_masuk', 'undangan_akan_datang', 'undangan_check_in_out', 'undangan_kadaluarsa'));
    }    
    
    
    public function index_accept(Request $request) {
        $undangan_id = $request->undangan_id;
        $undangan = UndanganPengunjung::findOrFail($undangan_id);
        $lokasis = lokasi::all();
        return view('host/konfirmasi_undangan', compact('undangan','lokasis'));
    }
    
    // public function acceptUndangan(Request $request, $id) {
    //     try {
    //         // Temukan undangan berdasarkan ID atau gagal jika tidak ditemukan
    //         $undangan = UndanganPengunjung::findOrFail($id);
    
    //         // Update status undangan menjadi 'diterima'
    //         $undangan->update([
    //             'status' => 'diterima',
    //         ]);
    
    //         // Mengumpulkan detail undangan
    //         $details = [
    //             'ID' => $undangan->id,
    //             'Nama' => $undangan->pengunjung->namaLengkap, // Asumsikan ada relasi pengunjung
    //             'Tanggal' => $undangan->waktu_temu,
    //             'Lokasi' => $undangan->ruangan,
    //             'Pesan' => $undangan->keterangan,
    //         ];
    
    //         // Mengonversi array detail undangan menjadi string JSON
    //         $detailsJson = json_encode($details);
    
    //         // Tentukan jalur direktori untuk menyimpan QR code
    //         $directory = storage_path('app/public/qr');
    
    //         // Jika direktori tidak ada, buat direktori tersebut
    //         if (!is_dir($directory)) {
    //             if (!mkdir($directory, 0777, true)) {
    //                 return redirect()->back()->with('error', 'Gagal membuat direktori untuk QR code.');
    //             }
    //         }
    
    //         // Tentukan jalur file untuk menyimpan QR code
    //         $qrFileName = 'qrimage-' . $id . '-' . $undangan->id . '.png';
    //         $qrImagePath = $directory . '/' . $qrFileName;
    
    //         // Generate QR code dengan detail undangan
    //         $qrCode = QrCode::format('png')->size(500)->generate($detailsJson);
    
    //         // Simpan QR code ke file
    //         if (file_put_contents($qrImagePath, $qrCode) === false) {
    //             return redirect()->back()->with('error', 'Gagal menyimpan QR code.');
    //         }
    
    //         // Redirect kembali dengan pesan sukses
    //         // return redirect('konfirmasi_kunjungan.show')->with('success', 'Undangan telah diterima.');
    //         session()->flash('konfirmasi_berhasil', true);
    //         return Redirect::route('konfirmasi_kunjungan.show')->with('konfirmasi_berhasil', 'Berhasil konfirmasi');;
    
    //     } catch (\Exception $e) {
    //         // Logging error message
    //         Log::error('Error accepting undangan: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima undangan.');
    //     }
    // }

    public function acceptUndangan(Request $request, $id)
    {
        try {
            // Find the invitation by ID or fail if not found
            $undangan = UndanganPengunjung::findOrFail($id);
    
            // Update invitation status to 'accepted'
            $undangan->update(['status' => 'diterima']);
    
            // Collect invitation details
            $details = [
                'ID' => $undangan->id,
                'Subjek' => $undangan->subject,
                'Nama' => $undangan->pengunjung->namaLengkap,
                'Kunjungan Dari' => $undangan->kunjungan_dari,
                'Waktu Temu' => $undangan->waktu_temu,
                'Waktu Kembali' => $undangan->waktu_kembali,
                'Host' => $undangan->host->nama,
                'Lokasi' => $undangan->lokasi->ruangan,
                'Pesan' => $undangan->keterangan,
                'Jenis Undangan' => $undangan->type,
                'Foto Profil' => asset('storage/profil/' . $undangan->pengunjung->foto_profil), // URL foto profil
            ];
    
            // Convert invitation details array to JSON string
            $detailsJson = json_encode($details);
    
            // Determine the directory path for saving the QR code
            $directory = storage_path('app/public/qr');
    
            // If directory does not exist, create it
            if (!is_dir($directory)) {
                if (!mkdir($directory, 0777, true)) {
                    return redirect()->back()->with('error', 'Failed to create directory for QR code.');
                }
            }
    
            // Determine the file path to save the QR code
            $qrFileName = 'qrimage-' . $id . '-' . $undangan->id . '.png';
            $qrImagePath = $directory . '/' . $qrFileName;
    
            // Generate QR code with invitation details
            $qrCode = QrCode::format('png')->size(500)->generate($detailsJson);
    
            // Save the QR code to file
            if (file_put_contents($qrImagePath, $qrCode) === false) {
                return redirect()->back()->with('error', 'Failed to save QR code.');
            }
    
            // Send QR code to visitor's WhatsApp using Fonnte
            $phoneNumber = $undangan->pengunjung->nomor_telepon;
            $message = "Halo {$undangan->pengunjung->namaLengkap}, undangan Anda telah diterima. Berikut adalah QR code untuk undangan Anda.";
            $mediaUrl = asset('storage/qr/' . $qrFileName);
    
            // Your Fonnte API token
            $token = env('FONNTE_API_TOKEN');
    
            // Check if the image file exists
            if (!file_exists($qrImagePath)) {
                return redirect()->back()->with('error', 'QR code file does not exist');
            }
    
            // Create a CURLFile object for the image
            $imageFile = curl_file_create($qrImagePath, mime_content_type($qrImagePath), basename($qrImagePath));
    
            $curl = curl_init();
    
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'target' => $phoneNumber,
                    'message' => $message,
                    'file' => $imageFile // Attach the image file
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token"
                ],
            ]);
    
            $response = curl_exec($curl);
    
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
                curl_close($curl);
                return redirect()->back()->with('error', "cURL Error: $error_msg");
            }
    
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
    
            if ($http_code != 200) {
                return redirect()->back()->with('error', "API returned status code $http_code. Response: $response");
            }
    
            // Redirect back with success message
            session()->flash('konfirmasi_berhasil', true);
            return Redirect::route('konfirmasi_kunjungan.show')->with('konfirmasi_berhasil', 'Berhasil konfirmasi');
    
        } catch (\Exception $e) {
            // Logging error message
            Log::error('Error accepting undangan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima undangan.');
        }
    }
    


    public function rejectUndangan(Request $request, $id)
    {
        try {
            // Temukan undangan berdasarkan ID atau gagal jika tidak ditemukan
            $undangan = UndanganPengunjung::findOrFail($id);

            // Update status undangan menjadi 'ditolak' dan simpan alasan penolakan
            $undangan->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
            ]);

            // Redirect kembali dengan pesan sukses
            return Redirect::route('konfirmasi_kunjungan.show')->with('success', 'Berhasil Menolak');
            
        } catch (\Exception $e) {
            // Logging error message
            Log::error('Error rejecting undangan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak undangan.');
        }
    }

    public function index_penolakan($id){

        $undangan = UndanganPengunjung::find($id); // Gantilah ini dengan cara Anda mendapatkan data undangan
        return view('host/alasan_penolakan', compact('undangan'));
    }

    public function scanQrCode(Request $request) {
        try {
            // Decode JSON dari QR code
            $details = json_decode($request->input('qr_data'), true);
    
            if (!$details || !isset($details['ID'])) {
                return response()->json(['error' => 'Invalid QR code data'], 400);
            }
    
            // Temukan undangan berdasarkan ID
            $undangan = UndanganPengunjung::find($details['ID']);
    
            if (!$undangan) {
                return response()->json(['error' => 'Undangan tidak ditemukan'], 404);
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
    
    public function index_buatkunjungan_host(){
        $pengunjung = Pengunjung::all();
        return view('host/buat_kunjungan_host', compact('pengunjung'));
    }
    

    public function index_konfirmasi_kunjungan()
    {
        // Mengambil data undangan yang terkait dengan host yang sedang login
        $hostId = Auth::guard('host')->id();
        $undangans = UndanganPengunjung::where('host_id', $hostId)->get();

        return view('host.konfirmasi_kunjungan', compact('undangans'));
    }

    public function index_riwayat_host(){
        // Mengambil ID host yang sedang login
        $hostId = Auth::guard('host')->id();
    
        // Mengambil undangan yang memiliki ID host yang sesuai dan sudah memiliki status 'Ditolak', 'Kadaluarsa', atau 'Selesai'
        $undangan = UndanganPengunjung::where('host_id', $hostId)
                                        ->whereIn('status', ['Ditolak', 'Kadaluarsa', 'Selesai'])
                                        ->get();
    
        return view('host.riwayat_host', compact('undangan'));
    }    

    public function show($id)
    {
        $undangan = UndanganPengunjung::findOrFail($id); // Assuming you retrieve the invitation by its ID
        return view('host/detailundangan', compact('undangan'));
    }
    
    public function store_undangan_host(Request $request)
    {
        // Validasi input
        $request->validate([
            'subject' => 'required|string|max:255',
            'keterangan' => 'required|string|max:255',
            'kunjungan_dari' => 'required|string|max:255',
            'waktu_temu' => 'required|date',
            'waktu_kembali' => 'nullable|date',
            'pengunjung_id' => 'required|exists:pengunjung,id',
            'type' => 'required|string|in:personal,group',
            'keperluan' => 'required|string|in:Pribadi,Pekerjaan',
        ]);
    
        if ($request->type === 'group') {
            $request->validate([
                'group_members' => 'required|array',
                'group_members.*.name' => 'required|string|max:255',
                'group_members.*.email' => 'required|email|max:255',
                'group_members.*.phone' => 'required|string|max:15',
            ]);
        }
    
        // Ambil host yang sedang login menggunakan guard host
        $host_id = auth()->guard('host')->id();
        $host = Host::find($host_id);
        if (!$host || !$host->lokasi_id) {
            return redirect()->back()->withErrors(['host_id' => 'Host tidak memiliki lokasi yang terkait.']);
        }
    
        // Tetapkan nilai pengunjung_id ke dalam array $request dan tambahkan status serta lokasi_id
        $request->merge(['host_id' => $host_id, 'status' => 'Diundang', 'lokasi_id' => $host->lokasi_id]);
    
        // Buat undangan
        $undangan = UndanganPengunjung::create($request->only([
            'subject', 'keterangan', 'status', 'kunjungan_dari', 'waktu_temu',
            'waktu_kembali', 'host_id', 'pengunjung_id', 'type', 'lokasi_id', 'keperluan'
        ]));
    
        // Jika tipe undangan adalah grup, tambahkan anggota grup
        if ($request->type === 'group') {
            foreach ($request->group_members as $member) {
                GroupMember::create([
                    'undangan_id' => $undangan->id,
                    'name' => $member['name'],
                    'email' => $member['email'],
                    'phone' => $member['phone']
                ]);
            }
        }
    
        // Redirect dengan pesan sukses
        session()->flash('buat_undangan_berhasil', true);
        return redirect()->route('undangan.show')->with('buat_undangan_berhasil', 'Undangan berhasil dibuat.');
    }
}    
