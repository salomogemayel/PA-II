@extends('admin.layouts.main')

@section('content')
<style>
    .custom-span {
        font-weight: 300; /* Atur tebal huruf */
        color: #333; /* Atur warna teks */
        font-size: 15px;
    }

    .custom-p {
        font-size: 18px; /* Atur ukuran teks */
        color: white; /* Atur warna teks */
        background-color: #62D9CD;
    }

    .card {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan pada kartu */
        border-radius: 25px; /* Buat sudut kartu lebih melengkung */
        overflow: hidden; /* Agar konten tidak keluar dari batas kartu */
        margin-top: 20px; /* Menambahkan jarak di atas kartu */
    }

    .btn {
        border-radius: 20px; /* Buat sudut tombol lebih melengkung */
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
</style>

@if($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ $errors->first() }}',
        });
    </script>
@endif

@if(session('success_tambahlokasi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tampilkan SweetAlert dengan pesan flash
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Menambahkan Lokasi!',
            text: '{{ session('success_tambahlokasi') }}',
        });
    </script>
@endif

@if(session('success_hapuslokasi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tampilkan SweetAlert dengan pesan flash
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Menghapus Lokasi!',
            text: '{{ session('success_hapuslokasi') }}',
        });
    </script>
@endif

@if(session('success_update_lokasi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Memperbarui Lokasi!',
            text: '{{ session('success_update_lokasi') }}',
        });
    </script>
@endif

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Lokasi</h4>
            <a href="{{ route('tambah_lokasi.show') }}" class="btn btn-success float-start">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
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
                            <th><strong>Gedung</strong></th>
                            <th><strong>Ruangan</strong></th>
                            <th><strong>Aksi</strong></th>
                        </tr>
                    </thead>
                    <tbody id="lokasiTableBody">
                        @php $i = 1 @endphp
                        @foreach($lokasis as $lokasi)
                        <tr class="custom-span lokasi-row">
                            <td><strong>{{ $i++ }}</strong></td>
                            <td>{{ $lokasi->ruangan }}</td>
                            <td>{{ $lokasi->lantai }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('index_edit.show', $lokasi->id) }}" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fa fa-edit"></i></a>
                                    <form id="delete-form-{{ $lokasi->id }}" action="{{ route('lokasi.destroy', $lokasi->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button onclick="confirmDelete(event, {{ $lokasi->id }})" class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(event, lokasiId) {
        event.preventDefault(); // Mencegah pengiriman formulir secara langsung

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Anda tidak akan dapat mengembalikan data ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + lokasiId).submit(); // Mengirimkan formulir DELETE jika pengguna mengonfirmasi
            }
        });
    }

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#lokasiTableBody .lokasi-row');

        console.log('Search input value:', filter); // Debug: print input value
        
        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            console.log('Row text:', text); // Debug: print row text
            
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection
