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
        color: white;
        background-color: #62D9CD; /* Atur warna background menjadi biru muda */
    }

    .card {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); /* Tambahkan bayangan */
    }

    .fade-up {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .fade-up.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

@if(session('success_tambahhost'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success Menambahkan Host!',
            text: '{{ session('success_tambahhost') }}',
        });
    </script>
@endif

@if(session('success_hapushost'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success Menghapus Host!',
            text: '{{ session('success_hapushost') }}',
        });
    </script>
@endif

@if(session('success_updatehost'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success Mengupdate Host!',
            text: '{{ session('success_updatehost') }}',
        });
    </script>
@endif

<div class="col-lg-12 fade-up">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Host</h4>
            <a href="tambahhost" class="btn btn-success float-start"><i class="fas fa-plus"></i> Tambah</a>
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
                            <th><strong>NO.</strong></th>
                            <th><strong>Name Lengkap</strong></th>
                            <th><strong>Divisi</strong></th>
                            <th><strong>Lokasi</strong></th>
                            <th><strong></strong></th>
                        </tr>
                    </thead>
                    <tbody id="hostTableBody">
                        @php $i = 1 @endphp
                        @foreach($hosts as $host)
                        <tr class="custom-span host-row fade-up">
                            <td><strong>{{ $i++ }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($host->foto_profil)
                                        <img src="{{ asset($host->foto_profil) }}" class="rounded-lg me-2" width="24" alt="">
                                    @else
                                        <img src="{{ asset('images/avatar/1.jpg') }}" class="rounded-lg me-2" width="24" alt="">
                                    @endif
                                    {{ $host->nama }}
                                    <span class="w-space-no"></span>
                                </div>
                            </td>
                            <td><div class="d-flex align-items-center">{{ $host->divisi->nama_divisi }}</div></td>
                            <td><div class="d-flex align-items-center">{{ $host->lokasi->ruangan }} {{ $host->lokasi->lantai }}</div></td>
                            <td>
                                <div class="d-flex">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#hostDetailModal{{ $host->id }}" class="btn btn-primary shadow btn-xs sharp me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('edit_host.show', $host->id) }}" class="btn btn-warning shadow btn-xs sharp me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>                                    
                                    <form id="delete-form-{{ $host->id }}" action="{{ route('hostadmin.destroy', $host->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button onclick="confirmDelete(event, {{ $host->id }})" class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
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

@foreach($hosts as $host)
<!-- Modal -->
<div class="modal fade" id="hostDetailModal{{ $host->id }}" tabindex="-1" aria-labelledby="hostDetailModalLabel{{ $host->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hostDetailModalLabel{{ $host->id }}">Detail Host</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="{{ $host->foto_profil ? asset($host->foto_profil) : asset('images/avatar/1.jpg') }}" class="rounded-lg" width="100" alt="Foto Profil">
                </div>
                <div>
                    <p><strong>Nama:</strong> {{ $host->nama }}</p>
                    <p><strong>Username:</strong> {{ $host->username }}</p>
                    <p><strong>Jenis Kelamin:</strong> {{ $host->jenis_kelamin }}</p>
                    <p><strong>Nomor Telepon:</strong> {{ $host->nomor_telepon }}</p>
                    <p><strong>Email:</strong> {{ $host->email }}</p>
                    <p><strong>Alamat:</strong> {{ $host->alamat }}</p>
                    <p><strong>Lokasi:</strong> {{ $host->lokasi->ruangan }}  {{ $host->lokasi->lantai }}</p>
                    <p><strong>Divisi:</strong> {{ $host->divisi->nama_divisi }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <!-- Jika Anda ingin menambahkan tombol aksi di modal, letakkan di sini -->
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function confirmDelete(event, hostId) {
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
                document.getElementById('delete-form-' + hostId).submit(); // Mengirimkan formulir DELETE jika pengguna mengonfirmasi
            }
        });
    }

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#hostTableBody .host-row');
        
        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });

    // Animasi fade-up
    document.addEventListener('DOMContentLoaded', function () {
        function handleScroll() {
            fadeUpElements.forEach(function (element) {
                if (isInViewport(element)) {
                    element.classList.add('visible');
                }
            });
        }

        // Get all elements with the class 'fade-up'
        const fadeUpElements = document.querySelectorAll('.fade-up');

        // Function to check if an element is in the viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }

        // Initial check on page load
        handleScroll();

        // Event listener for scroll
        window.addEventListener('scroll', handleScroll);
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>


@endsection
