@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f7f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card-container {
            position: relative;
        }

        .card {
            position: relative;
            border-radius: 15px;
            width: 400px;
            height: 250px;
            overflow: hidden;
            color: #000;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
            opacity: 0.3;
            z-index: 0;
        }

        .card * {
            position: relative;
            z-index: 1;
            text-shadow:
                -0.5px -0.5px 0 #fff,
                0.5px -0.5px 0 #fff,
                -0.5px  0.5px 0 #fff,
                0.5px  0.5px 0 #fff;
        }

        .card-header {
            background-color: #f8b600;
            color: black;
            font-weight: bold;
            padding: 5px;
            font-size: 13px;
            text-align: center;
            position: absolute;
            top: 15px;
            left: 80px;
            right: 15px;
            border-radius: 5px;
        }

        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 60px;
        }

        .profile-photo {
            position: absolute;
            top: 90px;
            left: 10px;
            width: 70px;
            height: 70px;
            border-radius: 10px;
            background-color: #ccc;
            object-fit: cover;
        }

        .info {
            position: absolute;
            top: 100px;
            left: 80px;
            right: 80px;
            font-size: 14px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.6);
            padding: 6px 10px;
            border-radius: 6px;
            overflow-wrap: break-word;
            white-space: normal;
        }

        .info p {
            margin: 4px 4px;
        }

        .qrcode {
            position: absolute;
            top: 90px;
            right: 45px;
            width: 70px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
        }

        .btn {
            background-color: #0a1a7a;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #122ca0;
        }

        .close-btn {
            position: absolute;
            top: -80px;
            right: -80px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }

        .close-btn:hover {
            background-color: #d63333;
        }

        a.close-link {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="card-container">

    {{-- ✅ Kalau SUDAH LOGIN, tampilkan tombol silang --}}
    @auth
        <a href="{{ route('home') }}" class="close-link">
            <button type="button" class="close-btn">&times;</button>
        </a>
    @endauth

    {{-- ✅ Kalau BELUM LOGIN, tampilkan tombol LOGIN --}}
    @guest
        <form action="{{ route('login') }}" method="GET" style="position: absolute; top: -80px; right: -100px;">
            <button class="btn" type="submit" style="background-color:#1b2a6e;">LOGIN</button>
        </form>
    @endguest

    <div class="card">
        <img src="{{ asset('unib.jpg') }}" alt="Logo" class="logo">
        <div class="card-header">
            PERPUSTAKAAN PRODI INFORMATIKA<br>UNIVERSITAS BENGKULU
        </div>

        <img src="{{ asset('storage/foto/'.$user->foto) }}" alt="Foto" class="profile-photo">

       <div class="info">
            <p><strong>Nama :</strong> {{ $user->nama }}</p>
            <p><strong>NPM :</strong> {{ $user->npm }}</p>
        </div>

        <div class="qrcode">
            {!! QrCode::format('svg')->size(100)->generate($user->npm) !!}
        </div>

        <div class="footer">
            KARTU TANDA ANGGOTA<br>
            PERPUSTAKAAN PRODI INFORMATIKA
        </div>
    </div>

    <button id="save-png" class="btn" type="button">SIMPAN PNG</button>

</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
document.getElementById('save-png').addEventListener('click', function () {
    html2canvas(document.querySelector('.card')).then(canvas => {
        const link = document.createElement('a');
        link.download = 'kartu_anggota.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
});
</script>

</body>
</html>
