@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-md-4">
        <div class="card text-center mb-3" style="height: 250px; background-color: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 60px;">
                    {{ $undangan_masuk }}
                </div>
                <div class="mt-3">
                    <i class="fa-solid fa-user cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-2" style="font-size: 12px;"><span style="font-weight: bold;">Menunggu Konfirmasi</span></p>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#undanganModal1">Selengkapnya</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center mb-3" style="height: 250px; background-color: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 60px;">
                    {{ $undangan_akan_datang }}
                </div>
                <div class="mt-3">
                    <i class="fa-solid fa-user-group cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-2" style="font-size: 12px;"><span style="font-weight: bold;">Yang Akan Datang</span></p>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#undanganModal2">Selengkapnya</button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center mb-3" style="height: 250px; background-color: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 60px;">
                    {{ $undangan_kadaluarsa }}
                </div>
                <div class="mt-3">
                    <i class="fa-solid fa-user-slash cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-2" style="font-size: 12px;"><span style="font-weight: bold;">Kadaluarsa</span></p>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#undanganModal3">Selengkapnya</button>
            </div>
        </div>
    </div>
</div>

Modal untuk Undangan Menunggu Konfirmasi
<div class="modal fade" id="undanganModal1" tabindex="-1" aria-labelledby="undanganModal1Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undanganModal1Label">Detail Undangan Menunggu Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($undangan_masuk as $undangan)
                <p><strong>Subjek:</strong> {{ $undangan->subject }}</p>
                <p><strong>Nama Host:</strong> {{ $undangan->host->nama }}</p>
                <p><strong>Waktu Temu:</strong> {{ $undangan->waktu_temu }}</p>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Undangan Yang Akan Datang -->
<div class="modal fade" id="undanganModal2" tabindex="-1" aria-labelledby="undanganModal2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undanganModal2Label">Detail Undangan Yang Akan Datang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($undangan_akan_datang as $undangan)
                <p><strong>Subjek:</strong> {{ $undangan->subject }}</p>
                <p><strong>Nama Host:</strong> {{ $undangan->nama_host }}</p>
                <p><strong>Waktu Temu:</strong> {{ $undangan->waktu_temu }}</p>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Undangan Kadaluarsa -->
<div class="modal fade" id="undanganModal3" tabindex="-1" aria-labelledby="undanganModal3Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undanganModal3Label">Detail Undangan Kadaluarsa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($undangan_kadaluarsa as $undangan)
                <p><strong>Subjek:</strong> {{ $undangan->subject }}</p>
                <p><strong>Nama Host:</strong> {{ $undangan->nama_host }}</p>
                <p><strong>Waktu Temu:</strong> {{ $undangan->waktu_temu }}</p>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


@endsection
