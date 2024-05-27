@extends('admin.layouts.main')

@section('content')
<style>
    .custom-span {
        font-weight: 300;
        color: #333;
        font-size: 15px;
    }

    .custom-p {
        font-size: 18px;
        color: white;
        background-color: #62D9CD;
    }

    .card {
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }
</style>

@if(session('success_tambahdivisi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Menambahkan Divisi!',
            text: '{{ session('success_tambahdivisi') }}',
        });
    </script>
@endif

@if(session('success_hapusdivisi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Menghapus Divisi!',
            text: '{{ session('success_hapusdivisi') }}',
        });
    </script>
@endif

@if(session('success_update_divisi'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Memperbarui Divisi!',
            text: '{{ session('success_update_divisi') }}',
        });
    </script>
@endif

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Divisi</h4>
            <button type="button" class="btn btn-success float-start" data-toggle="modal" data-target="#tambahDivisiModal">
                <i class="fas fa-plus"></i> Tambah
            </button>
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
                <table class="table table-responsive-md" id="divisiTable">
                    <thead>
                        <tr class="custom-p">
                            <th><strong>NO.</strong></th>
                            <th><strong>Nama Divisi</strong></th>
                            <th><strong>Aksi</strong></th>
                        </tr>
                    </thead>
                    <tbody id="divisiTableBody">
                        @php $i = 1 @endphp
                        @foreach($divisis as $divisi)
                        <tr class="custom-span divisi-row">
                            <td><strong>{{ $i++ }}</strong></td>
                            <td>{{ $divisi->nama_divisi }}</td>
                            <td>
                                <div class="d-flex">
                                    <!-- Tombol Edit -->
                                    <button class="btn btn-warning shadow btn-xs sharp me-1" onclick="showEditModal({{ $divisi->id }}, '{{ $divisi->nama_divisi }}')"><i class="fa fa-edit"></i></button>
                                    <!-- Tombol Hapus -->
                                    <form id="delete-form-{{ $divisi->id }}" action="{{ route('divisi.destroy', ['id' => $divisi->id]) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button onclick="confirmDelete(event, {{ $divisi->id }})" class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
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

<!-- Modal Tambah Divisi -->
<div class="modal fade" id="tambahDivisiModal" tabindex="-1" role="dialog" aria-labelledby="tambahDivisiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahDivisiModalLabel">Tambah Divisi Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('divisi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_divisi">Nama Divisi:</label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>      
        </div>
    </div>
</div>

<!-- Modal Edit Divisi -->
<div class="modal fade" id="editDivisiModal" tabindex="-1" role="dialog" aria-labelledby="editDivisiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDivisiModalLabel">Edit Divisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="edit_nama_divisi">Nama Divisi:</label>
                        <input type="text" class="form-control" id="edit_nama_divisi" name="nama_divisi">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (Optional if you need modal functionality with JavaScript) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>

<!-- SweetAlert2 for delete confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    function confirmDelete(event, divisiId) {
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
                document.getElementById('delete-form-' + divisiId).submit(); // Mengirimkan formulir DELETE jika pengguna mengonfirmasi
            }
        });
    }

    function showEditModal(divisiId, namaDivisi) {
        document.getElementById('edit_nama_divisi').value = namaDivisi;
        document.getElementById('editForm').action = `/divisi/${divisiId}`;
        $('#editDivisiModal').modal('show');
    }

    // Fungsi pencarian
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll('#divisiTableBody .divisi-row');
        
        rows.forEach(row => {
            let text = row.innerText.toUpperCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
</script>

@endsection
