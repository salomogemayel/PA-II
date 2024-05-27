<!DOCTYPE html>
<html>
<head>
    <title>Edit Host</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            background-color: #f9f9f9;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23B0DCE9" fill-opacity="1" d="M0,32L48,58.7C96,85,192,139,288,160C384,181,480,171,576,176C672,181,768,203,864,213.3C960,224,1056,224,1152,218.7C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
            border-radius: 50px;
            width: 600px;
            padding: 20px;
        }
        .form-control {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-lg-12 d-flex justify-content-center">
            <div class="card">
                <div class="card-header" style="background-color: white">
                    <h4 class="card-title text-center">Ubah Data Host</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('update_host', $host->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $host->nama }}" placeholder="Nama" required>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $host->username }}" placeholder="Username" required>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $host->alamat }}" placeholder="Alamat" required>
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ $host->nomor_telepon }}" placeholder="Nomor Telepon" required>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $host->email }}" placeholder="Email" required>
                        <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin" value="{{ $host->jenis_kelamin }}" placeholder="Jenis Kelamin" required>
                        <select class="form-control" id="divisi" name="divisi" required>
                            <option value="">Pilih Divisi</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ $host->divisi_id == $division->id ? 'selected' : '' }}>{{ $division->nama_divisi }}</option>
                            @endforeach
                        </select>
                        
                        
                        <select class="form-control" id="lokasi" name="lokasi" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $host->lokasi_id == $location->id ? 'selected' : '' }}>{{ $location->ruangan }} {{ $location->lantai }}</option>
                            @endforeach
                        </select>                        
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto_profil" name="foto_profil" accept="image/*">
                            <label class="custom-file-label" for="foto_profil">Pilih Foto Profile</label>
                          </div>
                        <button type="submit" class="btn btn-success btn-block mt-3" style="border-radius: 20px">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Mengubah teks label sesuai nama file yang dipilih
        document.getElementById('foto_profil').addEventListener('change', function() {
          var fileName = this.files[0].name;
          var nextSibling = this.nextElementSibling;
          nextSibling.innerText = fileName;
        });
      </script>
</body>
</html>
