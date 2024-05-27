@extends('entry_point.layouts.main')

@section('content')
<style>
    .custom-span {
        font-weight: 300;
        color: #333;
        font-size: 14px;
    }

    .custom-p {
        color: white;
        background-color: #62D9CD;
    }

    .card {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        border-radius: 25px;
        overflow: hidden;
        margin-top: 20px;
        background-color: #fff;
    }

    .btn {
        border-radius: 20px;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .modal-body .row {
        margin-bottom: 10px;
    }

    .modal-body .col-5,
    .modal-body .col-1,
    .modal-body .col-6 {
        display: flex;
        align-items: center;
    }

    .modal-body .col-1 {
        justify-content: center;
    }

    .modal-title {
        text-align: center;
        width: 100%;
    }
</style>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Informasi Tamu</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row mb-3">
                    <div class="col-md-9"></div> 
                    <div class="col-md-3 text-end">
                        <a href="{{ route('riwayat.cetak') }}" class="btn btn-primary me-2 btn-custom" target="_blank">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                </div>
                <li class="nav-item col-md-3">
                    <div class="input-group search-area">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search here...">
                        <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                    </div>     
                </li>
                <br>
                <table class="table table-responsive-md">
                    <thead>
                        <tr class="custom-p">
                            <th><strong>No.</strong></th>
                            <th><strong>Visitor</strong></th>
                            <th><strong>Host</strong></th>
                            <th><strong>Divisi</strong></th>
                            <th><strong>Kunjungan Dari</strong></th>
                            <th><strong>Jam Kedatangan</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Aksi</strong></th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTableBody">
                        @foreach ($undangan as $index => $item)
                        <tr class="custom-span riwayat-row">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->pengunjung->namaLengkap }}</td>
                            <td>{{ $item->host->nama }}</td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->lokasi->ruangan }} {{ $item->lokasi->lantai }}</td>
                            <td>{{ $item->waktu_temu }}</td>
                            <td>@if($item->status == 'Diterima')
                                    <span class="badge light badge-success">
                                        <i class="fa fa-circle text-success me-1"></i>
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#undanganDetailModal{{ $item->id }}" class="btn btn-primary shadow btn-xs sharp me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>                     
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($undangan as $item)
<!-- Modal -->
<div class="modal fade" id="undanganDetailModal{{ $item->id }}" tabindex="-1" aria-labelledby="undanganDetailModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="undanganDetailModalLabel{{ $item->id }}">Informasi Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-5">Visitor</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->pengunjung->namaLengkap }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Host</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->host->nama }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Divisi</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->host->divisi->nama_divisi }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Kunjungan dari</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->kunjungan_dari }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Waktu Temu</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->waktu_temu }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Waktu Kembali</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->waktu_kembali }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Keperluan</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->subject }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Jenis Kunjungan</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->type }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Ruangan</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->lokasi->ruangan }} {{ $item->lokasi->lantai }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Nomor Telepon</div>
                        <div class="col-1">:</div>
                        <div class="col-6">0{{ $item->pengunjung->nomor_telepon }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Email</div>
                        <div class="col-1">:</div>
                        <div class="col-6">{{ $item->pengunjung->email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5">Status</div>
                        <div class="col-1">:</div>
                        <div class="col-6">
                            @if($item->status == 'diterima')
                                <span class="badge light badge-success">
                                    <i class="fa fa-circle text-success me-1"></i>
                                    {{ $item->status }}
                                </span>
                            @endif 
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->
@endforeach

<script>
    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');

        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection
