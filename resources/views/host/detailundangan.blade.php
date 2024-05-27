<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/jpeg" href="image/Flogin.jpg">
<title>Konfirmasi Undangan</title>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
  body {
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23B0DCE9" fill-opacity="1" d="M0,32L48,58.7C96,85,192,139,288,160C384,181,480,171,576,176C672,181,768,203,864,213.3C960,224,1056,224,1152,218.7C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
    position: relative;
    height: 100vh;
    padding: 20px;
    overflow: hidden;
    background-size: cover;
  }
  .card-custom {
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    padding: 20px;
    max-width: 750px;
    margin: 0 auto;
    margin-top: 50px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
  }
  .card-header {
    color: black;
    padding: 15px;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    background-color: white;
  }
  .card-title {
    margin-bottom: 0;
    font-size: 24px;
  }
  .card-body {
    padding-top: 30px;
  }
  .row > div {
    margin-bottom: 20px;
  }
  p {
    margin-bottom: 5px;
    color: #555;
  }
  .value {
    font-weight: bold;
    font-size: 16px;
    color: #333;
  }
  .note-field {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
  }
  .btn-accept {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    margin-right: 10px;
  }
  .btn-reject {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
  }

  .note-field {
    border-radius: 20px;
    height: 110px;
  }
</style>
</head>
<body>

    <div class="container">
        <div class="card card-custom animate__animated animate__fadeInUp">
            <form action="{{ route('accept.undangan', ['undangan_id' => $undangan->id]) }}" method="POST">
                @csrf
            <div class="card-header">
                <h2 class="card-title">Informasi Kunjungan</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p>Subject:</p>
                        <div class="value">{{ $undangan->subject }}</div>

                        <p>Nama Pengunjung:</p>
                        <div class="value">{{ $undangan->Pengunjung->namaLengkap }}</div>

                        <p>Kunjungan dari:</p>
                        <div class="value">{{ $undangan->kunjungan_dari }}</div>

                        <p>Nomor Telepon:</p>
                        <div class="value">{{ $undangan->Pengunjung->nomor_telepon }}</div>

                        <p>Note:</p>
                        <div class="note-field" readonly>{{ $undangan->keterangan }}</div>
                    </div>
                    <div class="col-md-6">
                        <p>Waktu Kedatangan:</p>
                        <div class="value">{{ $undangan->waktu_temu }}</div>

                        <p>Waktu Kepulangan:</p>
                        <div class="value">{{ $undangan->waktu_kembali }}</div>

                        <p>Keperluan Kunjungan:</p>
                        <div class="value">{{ $undangan->keperluan }}</div>

                        <p>Jenis Kunjungan:</p>
                        <div class="value">{{ $undangan->type }}</div>
                    </div>
                </div>
                <div class="text-end mt-4" style="justify-content:right; display:flex;" >
                    <button type="submit" class="btn-accept" style="border-radius: 20px;">Terima</button>
                    <a href="{{ route('index_penolakan.show', ['id' => $undangan->id]) }}" class="btn-reject" style="border-radius: 20px;">Tolak</a>
                </div>
            </div>
            </form>
        </div>
        </div>
    </div>
    
</body>
</html>
