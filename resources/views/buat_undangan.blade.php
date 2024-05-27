@extends('layouts.app')

    @section('content')
    <style>
        .card {
            border-radius: 30px;
            box-shadow: 0 10px 16px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 20px;
            border-color: #ccc;
        }

        .form-group h4 {
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 20px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .card-header {
            color: white;
            font-size: 25px;
            border-radius: 25px;
            padding: 20px 0;
            text-align: center;
        }

        .group-member {
            margin-bottom: 10px;
        }
    </style>
    @if(session('buat_undangan_berhasil'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success Membuat Undangan!',
                text: '{{ session('success_updatehost') }}',
            });
        </script>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mt-5">
        <form action="{{ route('janji_temu.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header" style="justify-content: center">
                    <h2 class="card-title mb-2" style="font-size:27px;"><b>Form Undangan</b></h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject" required>
                        </div>
                        <div class="col-md-6">
                            <select name="keperluan" id="keperluan" class="form-control" required>
                                <option value="" disabled selected>Keperluan</option>
                                <option value="Pribadi">Pribadi</option>
                                <option value="Pekerjaan">Pekerjaan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="kunjungan_dari" name="kunjungan_dari" class="form-control" placeholder="Kunjungan Dari" required>
                        </div>
                        <div class="col-md-6">
                            <input type="datetime-local" id="waktu_temu" name="waktu_temu" class="form-control" placeholder="Waktu Temu" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="datetime-local" id="waktu_kembali" name="waktu_kembali" class="form-control" placeholder="Waktu Kembali">
                        </div>
                        <div class="col-md-6">
                            <select name="host_id" id="host_id" class="form-control custom-dropdown" required>
                                <option value="" disabled selected>Host</option>
                                @foreach($hosts as $host)
                                    <option value="{{ $host->id }}" data-nama="{{ $host->nama }}" data-divisi="{{ $host->divisi->nama_divisi }}" data-ruangan="{{ $host->lokasi->ruangan }} {{ $host->lokasi->lantai }}">
                                        {{ $host->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="host_details" style="display: none;">
                                <p>Host yang ingin diundang:</p>
                                <p><strong>Nama:</strong> <span id="host_nama">Frans Panjaitan</span></p>
                                <p><strong>Divisi:</strong> <span id="host_divisi">Divisi</span></p>
                                <p><strong>Ruangan:</strong> <span id="host_ruangan">Ruangan</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" required>
                        </div>
                        <div class="col-md-6">
                            <select name="type" id="type" class="form-control" required>
                                <option value="" disabled selected>Tipe</option>
                                <option value="personal">Personal</option>
                                <option value="group">Group</option>
                            </select>
                        </div>
                    </div>
                    <div id="groupMembers" style="display: none;">
                        <h4>Group Members</h4>
                        <div class="row mb-3 group-member">
                            <div class="col-md-3">
                                <input type="text" id="member_name" name="group_members[0][name]" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-md-3">
                                <input type="email" id="member_email" name="group_members[0][email]" class="form-control" placeholder="Email">
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="member_phone" name="group_members[0][phone]" class="form-control" placeholder="Phone">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-member">Remove</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="addGroupMember" class="btn btn-secondary rounded-pill">Add Member</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" style="text-align: center">
                    <button type="submit" class="btn btn-success rounded-pill">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#type').change(function () {
                if ($(this).val() === 'group') {
                    $('#groupMembers').show();
                } else {
                    $('#groupMembers').hide();
                }
            });

            let memberIndex = 1;
            $('#addGroupMember').click(function () {
                const newMember = $('.group-member:first').clone().removeAttr('id');
                newMember.find('input').each(function () {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, `[${memberIndex}]`);
                    $(this).attr('name', newName).val('');
                });
                newMember.insertBefore('#addGroupMember').show();
                memberIndex++;
            });

            $(document).on('click', '.remove-member', function () {
                $(this).closest('.group-member').remove();
            });

            $('#host_id').change(function () {
                var selectedOption = $(this).children("option:selected");
                var nama = selectedOption.data('nama');
                var divisi = selectedOption.data('divisi');
                var ruangan = selectedOption.data('ruangan');
                
                // Menampilkan detail host di bawah dropdown
                $('#host_nama').text(nama);
                $('#host_divisi').text(divisi);
                $('#host_ruangan').text(ruangan);
                $('#host_details').show();
            });

            // Mengatur tanggal dan waktu minimum untuk input waktu_temu dan waktu_kembali
            var now = new Date().toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
            $('#waktu_temu').attr('min', now);
            $('#waktu_kembali').attr('min', now);

            // Ketika pengguna memilih tanggal atau waktu pada input waktu_temu
            $('#waktu_temu').change(function () {
                // Mendapatkan nilai input
                var selectedDateTime = new Date($(this).val()).getTime();
                var nowDateTime = new Date().getTime();

                // Membandingkan dengan waktu saat ini
                if (selectedDateTime < nowDateTime) {
                    alert('Waktu tidak dapat dipilih sebelum waktu saat ini.');
                    $(this).val(now); // Kembalikan ke nilai minimum jika memilih waktu sebelum waktu saat ini
                }
            });

            // Ketika pengguna memilih tanggal atau waktu pada input waktu_kembali
            $('#waktu_kembali').change(function () {
                // Mendapatkan nilai input
                var selectedDateTime = new Date($(this).val()).getTime();
                var nowDateTime = new Date().getTime();

                // Membandingkan dengan waktu saat ini
                if (selectedDateTime < nowDateTime) {
                    alert('Waktu tidak dapat dipilih sebelum waktu saat ini.');
                    $(this).val(now); // Kembalikan ke nilai minimum jika memilih waktu sebelum waktu saat ini
                }
            });
        });
    </script>
@endsection

