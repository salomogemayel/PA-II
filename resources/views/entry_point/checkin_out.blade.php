@extends('entry_point.layouts.main')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h3>{{ __('Scan QR Code') }}</h3>
                </div>
                <div class="card-body text-center">
                    <div class="video-container mb-4 position-relative" style="border: 2px solid #ddd; border-radius: 10px; overflow: hidden;">
                        <video id="video" width="100%" height="auto" style="transform: scaleX(-1);"></video>
                        <canvas id="canvas" width="300" height="200" style="display: none;"></canvas>
                    </div>
                    <div id="resultContainer" class="alert alert-info d-none">
                        <p id="result"></p>
                        <div id="cardContainer" class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title" id="name"></h5>
                                <p class="card-text" id="description"></p>
                                <img id="resultImage" src="" alt="Scanned Image" style="max-width: 100%;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const resultContainer = document.getElementById('resultContainer');
    const result = document.getElementById('result');
    const cardContainer = document.getElementById('cardContainer');
    const resultImage = document.getElementById('resultImage');
    const context = canvas.getContext('2d');
    const name = document.getElementById('name');
    const description = document.getElementById('description');

    // Fungsi untuk memulai video stream
    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                video.srcObject = stream;
                video.setAttribute('playsinline', true); // diperlukan untuk iOS
                video.play();
                requestAnimationFrame(scanQRCode);
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
            });
    }

    // Fungsi untuk memindai QR code
    function scanQRCode() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });

            if (code) {
                try {
                    const qrData = JSON.parse(code.data);
                    result.textContent = 'Data QR berhasil dibaca.';
                    resultContainer.classList.remove('d-none');
                    
                    // Menampilkan data pada kartu
                    name.textContent = qrData['ID'] || 'Nama tidak tersedia';
                    description.textContent = qrData['Deskripsi'] || 'Deskripsi tidak tersedia';

                    if (qrData['Foto Profil']) {
                        resultImage.src = qrData['Foto Profil'];
                    } else {
                        resultImage.src = '';
                        resultImage.alt = 'No image found in QR code.';
                    }

                    // Mengirim data ke server jika diperlukan
                    $.ajax({
                        url: '/scan_qr_code1', // Perbaikan pada URL
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ id: qrData['ID'] }), // Mengirimkan hanya ID
                        success: function(data) {
                            if (data.error) {
                                alert('Error: ' + data.error);
                            } else {
                                alert('Success: ' + JSON.stringify(data));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error sending QR data:', error);
                            alert('Failed to send QR data. Please try again later.');
                        }
                    });

                } catch (e) {
                    result.textContent = 'Gagal membaca data QR.';
                }
            } else {
                requestAnimationFrame(scanQRCode);
            }
        } else {
            requestAnimationFrame(scanQRCode);
        }
    }

    startVideo();
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

@endsection
