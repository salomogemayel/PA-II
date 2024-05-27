@extends('host.layouts.main')

@section('content')
<div class="row justify-content-center mb-4">
    <!-- Other cards remain the same -->
    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $undangan_masuk }}
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                    <i class="fa-solid fa-user cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Undangan Masuk</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $undangan_akan_datang }} 
                </div>
                <p class="card-text mt-3" style="font-size: 15px;"><i class="fa-regular fa-calendar"></i> <span style="font-weight: bold;">Hari Ini</span></p>
                <div class="mt-4">
                    <i class="fa-solid fa-user-group cardb-icon" style="font-size: 20px;"></i>
                </div>
                <p class="card-text mt-3" style="font-size: 15px; "><span style="font-weight: bold;">Yang Akan Datang</span></p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center mb-3" style="height: 270px; background-color: white; border-radius: 15px; box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="card-content display-4 font-weight-bold" style="font-size: 80px;">
                    {{ $undangan_check_in_out }} 
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
                    {{ $undangan_kadaluarsa }} 
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
@endsection
