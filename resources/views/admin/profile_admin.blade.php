@extends('admin.layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white" style="font-size: 20px">{{ __('Admin Profile') }}</div>

                <div class="card-body text-center">
                    <!-- Display Profile Picture and Username -->
                    <div style="display: flex;">
                        <div class="mb-4 d-flex align-items-center col-md-4" style="justify-content:center;">
                            <img src="{{ asset('images/profile/pic1.jpg') }}" class="rounded-circle" alt="Profile Picture" style="width: 100px; height: 100px;">
                        </div>
                        <div class="mb-6 d-flex align-items-center m-5">
                            <div style="color: #333; font-weight: bold; margin-bottom: 5px; font-size: 18px;">{{ $admin->username }}</div>
                        </div>
                    </div>

                    <!-- Display Name and Other Details -->
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control-plaintext text-center" style="color: #333; font-weight: bold; font-size: 16px;" name="name" value="{{ $admin->Nama }}" readonly>
                        </div>
                        <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control-plaintext text-center" style="color: #333; font-weight: bold; font-size: 16px;" name="username" value="{{ $admin->username }}" readonly>
                        </div>
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        <div class="col-md-6" style="position: relative;">
                            <input id="password" type="password" class="form-control-plaintext text-center" style="color: #333; font-weight: bold; font-size: 16px;" name="password" value="password_placeholder" readonly>
                            <i class="fa fa-eye" id="togglePassword" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <div class="form-group row mt-4 mb-0">
                        <div class="col-md-12 text-center">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>
@endsection
