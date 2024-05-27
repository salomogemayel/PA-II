@extends('entry_point.layouts.main')

@section('content')

<style>
    .cardb {
        width: 250px;
        height: 130px;
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-direction: row;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .number {
        font-size: 45px;
        font-weight: bold;
        color: #828282;
    }
    .title {
        font-size: 15px;
        color: #828282;
    }
    .cardb-icon {
        font-size: 30px;
        color: #828282;
    }

    .custom-card {
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adds shadow */
        margin-bottom: 20px;
    }

    .avatar-stack {
        display: flex;
        align-items: center;
    }
    .avatar-stack img {
        border: 2px solid white;
        border-radius: 50%;
        margin-left: -10px;
        width: 50px;
        height: 50px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .extra-users {
        font-size: 18px;
        color: #333;
        margin-left: 10px;
        font-weight: bold;
    }

    .recent-card {
        height: auto;
        padding: 15px;
    }

    .recent-card .card-title {
        margin-bottom: 10px;
    }

</style>

<div class="row justify-content-center mb-4">
    <!-- Other cards remain the same -->
    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $semua_kunjungan }}
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                    <i class="fa-solid fa-user-group cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Semua Kunjungan</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $kunjungan_hari_ini }}
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                    <i class="fa-solid fa-user cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Yang akan datang</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $kunjungan_check_in_out }}
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                   <i class="fa-solid fa-user-check cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Check In/Out</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $kunjungan_kadaluarsa }}
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                    <i class="fa-solid fa-user-slash cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Kadaluarsa</span></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Bagian tabel untuk menampilkan kunjungan hari ini -->
    <div class="col-lg-8">
        <div class="card custom-card">
            <div class="card-header">
                <h4 class="card-title">Kunjungan Hari Ini</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <li class="nav-item col-md-3">
                        <div class="input-group search-area">
                            <input type="text" class="form-control" placeholder="Search here...">
                            <span class="input-group-text">
                                <a href="javascript:void(0)">
                                    <i class="flaticon-381-search-2"></i>
                                </a>
                            </span>
                        </div>
                    </li>
                    <br>
                    <!-- Tabel untuk menampilkan kunjungan hari ini -->
                    <table class="table table-responsive-md">
                        <thead>
                            <tr class="custom-p">
                                <th><strong>NO.</strong></th>
                                <th><strong>Nama Divisi</strong></th>
                                <th><strong>Aksi</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Isi tabel dapat ditambahkan di sini --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Recent Visitation -->
    <div class="col-lg-4">
        <div class="card recent-card custom-card">
            <h5 class="card-title">Recent Visitation</h5>
            <div class="avatar-stack">
                @foreach($recentUsers as $user)
                    <img src="{{ $user->foto_profil }}" alt="{{ $user->name }}">
                @endforeach
                @if($recentUsers->count() > 5)
                    <span class="extra-users">+{{ $recentUsers->count() - 5 }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
@endsection
