<?php

namespace App\Http\Controllers;

use App\Models\GroupMember;
use App\Models\Host;
use App\Models\lokasi;
use App\Models\Pengunjung;
use App\Models\PengunjungUndangan;
use App\Models\UndanganPengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UndanganPengunjungController extends Controller
{
    public function index_janji_temu(){
        $hosts = Host::all();
        $pengunjungs = Pengunjung::all();
        return view('janji_temu', compact('hosts','pengunjungs'));
    }

    public function index_undangan(){
        $hosts = Host::all();
        $pengunjungs = Pengunjung::all();
        return view('buat_undangan', compact('hosts','pengunjungs'));
    }

    public function create()
    {
        return view('undangan.create');
    }

    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'subject' => 'required|string|max:255',
        'keterangan' => 'required|string|max:255',
        'kunjungan_dari' => 'required|string|max:255',
        'waktu_temu' => 'required|date',
        'waktu_kembali' => 'nullable|date',
        'host_id' => 'required|exists:host,id',
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

    // Ambil lokasi_id dari tabel hosts berdasarkan host_id
    $host = Host::find($request->host_id);
    if (!$host || !$host->lokasi_id) {
        return redirect()->back()->withErrors(['host_id' => 'Host tidak memiliki lokasi yang terkait.']);
    }

    // Tetapkan nilai pengunjung_id ke dalam array $request dan tambahkan status serta lokasi_id
    $request->merge(['pengunjung_id' => Auth::id(), 'status' => 'Menunggu', 'lokasi_id' => $host->lokasi_id]);

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








// public function store(Request $request)
// {
//     // Validasi request
//     $request->validate([
//         'subject' => 'required|string|max:255',
//         'keterangan' => 'required|string',
//         'kunjungan_dari' => 'required|string',
//         'waktu_temu' => 'nullable|date',
//         'waktu_kembali' => 'nullable|date',
//         'lokasi_id' => 'nullable|exists:lokasi,id',
//         'host_id' => 'required|exists:host,id',
//         'pengunjung_id' => 'required|array',
//         'pengunjung_id.*' => 'exists:pengunjung,id',
//         'type' => 'required|string'
//     ]);

//     // Dapatkan ID pengunjung dari pengguna yang sedang login
//     $loggedInUserId = Auth::id();

//     // Insert ke tabel undangan_pengunjung
//     $undangan = UndanganPengunjung::create([
//         'subject' => $request->subject,
//         'keterangan' => $request->keterangan,
//         'status' => $request->status ?? 'Menunggu',
//         'kunjungan_dari' => $request->kunjungan_dari,
//         'waktu_temu' => $request->waktu_temu,
//         'waktu_kembali' => $request->waktu_kembali,
//         'lokasi_id' => $request->lokasi_id,
//         'host_id' => $request->host_id,
//         'pengunjung_id' => $loggedInUserId,
//         'type' => $request->type,
//     ]);

//     // Gabungkan ID pengunjung yang dipilih dengan ID pengguna yang sedang login
//     $pengunjungIds = $request->pengunjung_id;
//     if (!in_array($loggedInUserId, $pengunjungIds)) {
//         $pengunjungIds[] = $loggedInUserId;
//     }

//     // Insert ke tabel pengunjung_undangan
//     $undangan->pengunjungs()->attach($pengunjungIds);

//     return response()->json(['message' => 'Data berhasil disimpan', 'undangan' => $undangan], 201);
// }


    public function index_beranda()
    {
        $PengunjungID = Auth::id(); // Get the logged-in user's ID
        $undanganMasuk = UndanganPengunjung::where('pengunjung_id', $PengunjungID)->where('status', 'Menunggu')->count();
        $undanganAkanDatang = UndanganPengunjung::where('pengunjung_id', $PengunjungID)->whereIn('status', ['Diterima', 'Diundang'])->count();
        $undanganKadaluarsa = UndanganPengunjung::where('pengunjung_id', $PengunjungID)->where('status', 'Kadaluarsa')->count();

        $data = [
            'title' => 'Dashboard',
            'undangan_masuk' => $undanganMasuk,
            'undangan_akan_datang' => $undanganAkanDatang,
            'undangan_kadaluarsa' => $undanganKadaluarsa,
        ];

        return view('beranda', $data);
    }

    public function index_beranda_detail()
{
    // Mendapatkan ID pengguna yang sedang login
    $user_id = Auth::id();

    // Mengambil undangan yang memiliki status 'Menunggu Konfirmasi'
    $undangan_masuk = UndanganPengunjung::where('pengunjung_id', $user_id)
        ->where('status', 'Menunggu ')
        ->get();

    // Mengambil undangan yang memiliki status 'Yang Akan Datang'
    $undangan_akan_datang = UndanganPengunjung::where('pengunjung_id', $user_id)
        ->whereIn('status', ['Diterima', 'Diundang'])
        ->get();

    // Mengambil undangan yang memiliki status 'Kadaluarsa'
    $undangan_kadaluarsa = UndanganPengunjung::where('pengunjung_id', $user_id)
        ->where('status', 'Kadaluarsa')
        ->get();

    return view('beranda', compact('undangan_masuk', 'undangan_akan_datang', 'undangan_kadaluarsa'));
}


}
