@extends('host.layouts.main')

@section('content')
<style>
    .custom-span {
        font-weight: 300;
        color: #333;
        font-size: 15px;
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

    .form-select.custom-red {
        background-color: #62D9CD;
        color: white;
        height: calc(3em + 0.75rem + 2px);
    }

    .form-select.custom-red option {
        color: black;
    }

    .search-area .form-control {
        height: calc(2.5em + 0.75rem + 2px);
    }
    .custom-p {

    background-color: #62D9CD; /* Ubah warna teks menjadi hitam */
    }

    .btn-custom,
    .input-custom,
    .select-custom {
        width: 150px; /* Atur lebar tombol, input, dan dropdown filter */
    }

    /* Responsif */
    @media (max-width: 768px) {
        .btn-custom,
        .input-custom,
        .select-custom {
            width: 100%; /* Ubah lebar menjadi 100% pada layar kecil */
        }
    }

</style>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="row mb-3">
                    <select id="statusFilter" class="form-select custom-red select-custom me-2 ms-auto" aria-label="Filter">
                        <option value="">Semua Kategori</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="kadaluarsa">Kadaluarsa</option>
                        <option value="selesai">Selesai</option>
                    </select>   
                </div>
                <div class="col-md-3">
                    <div class="input-group search-area">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search here...">
                        <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                    </div>
                </div>
                <table class="table table-responsive-md">
                    <thead>
                        <tr class="custom-p">
                            <th><strong>No.</strong></th>
                            <th><strong>Nama Pengunjung</strong></th>
                            <th><strong>Subjek</strong></th>
                            <th><strong>Waktu</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Aksi</strong></th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTableBody">
                        @php $i = 1 @endphp
                        @foreach ($undangan as $item)
                        <tr class="custom-span riwayat-row">
                            <td><strong>{{ $i++ }}</strong></td>
                            <td>{{ $item->pengunjung->namaLengkap }}</td>
                            <td>{{ $item->subject }}</td>
                            <td>{{ $item->waktu_temu }}</td>
                            <td>
                                @if($item->status == 'Ditolak')
                                <span class="badge light badge-danger">
                                    <i class="fa fa-circle text-danger me-1"></i>
                                    {{ $item->status }}
                                </span>
                                @elseif($item->status == 'Kadaluarsa')
                                    <span class="badge light badge-secondary">
                                        <i class="fa fa-circle text-secondary me-1"></i>
                                        {{ $item->status }}
                                    </span>
                                @elseif($item->status == 'Selesai')
                                    <span class="badge light badge-success">
                                        <i class="fa fa-circle text-success me-1"></i>
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <!-- Tombol detail -->
                                <a href="#" data-bs-toggle="modal" data-bs-target="#undanganDetailModal{{ $item->id }}" class="btn btn-primary shadow btn-xs sharp me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Kontrol Pagination -->
                <div class="pagination-controls">
                    <ul class="pagination"></ul>
                </div>
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
                <h5 class="modal-title" id="undanganDetailModalLabel{{ $item->id }}">Riwayat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-5">Nama Lengkap</div>
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
                            @if($item->status == 'Ditolak')
                                <span class="badge light badge-danger">
                                    <i class="fa fa-circle text-danger me-1"></i>
                                    {{ $item->status }}
                                </span>
                                @elseif($item->status == 'Kadaluarsa')
                                    <span class="badge light badge-secondary">
                                        <i class="fa fa-circle text-secondary me-1"></i>
                                        {{ $item->status }}
                                    </span>
                                @elseif($item->status == 'Selesai')
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

    // Fungsi filter status
    document.getElementById('statusFilter').addEventListener('change', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');

        rows.forEach(row => {
            let status = row.querySelector('td:nth-child(5) .badge').innerText.toUpperCase();
            row.style.display = filter === '' || status.includes(filter) ? '' : 'none';
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        const rowsPerPage = 10;
        const rows = $('#riwayatTableBody .riwayat-row');
        const rowsCount = rows.length;
        const pageCount = Math.ceil(rowsCount / rowsPerPage);
        const paginationControls = $('.pagination-controls .pagination');

        function displayRows(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.hide();
            rows.slice(start, end).show();
        }

        function buildPagination() {
            paginationControls.empty();
            for (let i = 1; i <= pageCount; i++) {
                paginationControls.append(`<li class="page-item"><a href="#" class="page-link">${i}</a></li>`);
            }
        }

        paginationControls.on('click', 'a', function (e) {
            e.preventDefault();
            const page = $(this).text();
            displayRows(page);
            $(this).closest('li').addClass('active').siblings().removeClass('active');
        });

        // Initialize
        displayRows(1);
        buildPagination();
        paginationControls.find('li:first-child').addClass('active');
    });
</script>

<script>
    // Fungsi filter tanggal
    document.getElementById('tanggalFilter').addEventListener('change', function() {
        let selectedDate = this.value;
        let rows = document.querySelectorAll('#riwayatTableBody .riwayat-row');

        rows.forEach(row => {
            let tanggalUndangan = row.querySelector('td:nth-child(7)').innerText.trim(); // Anda perlu menyesuaikan indeks kolomnya
            if (selectedDate === '') {
                row.style.display = '';
            } else {
                // Filter berdasarkan tanggal yang dipilih
                if (tanggalUndangan === selectedDate) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection