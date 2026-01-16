@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota Library - Retro Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --maroon: #7C170D; 
            --navy-deep: #141A45; 
            --ivory: #ECE1D5; 
            --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #d1d5db;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            position: relative;
            width: 480px; /* Sedikit lebih lebar untuk QR besar */
            height: 280px;
            background-color: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
        }

        /* AKASEN BACKGROUND FT.JPG */
        .card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
            opacity: 0.12; /* Transparan agar tidak mengganggu teks */
            z-index: 0;
        }

        .top-bar {
            height: 12px;
            background-color: var(--navy-deep);
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .header {
            padding: 20px 25px 10px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .logo-box {
            width: 55px;
            height: 55px;
            background: rgba(236, 225, 213, 0.8);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .header-text h1 {
            margin: 0;
            font-size: 16px;
            color: var(--maroon);
            font-weight: 800;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 11px;
            color: var(--navy-deep);
            font-weight: 700;
        }

        .content {
            display: flex;
            padding: 15px 25px;
            gap: 25px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .photo-frame {
            width: 100px;
            height: 130px;
            border-radius: 12px;
            border: 3px solid var(--white);
            overflow: hidden;
            box-shadow: 6px 6px 0px var(--maroon);
            background: var(--ivory);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .details {
            flex-grow: 1;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #4b5563;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .value {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy-deep);
            margin-bottom: 15px;
        }

        /* QR CODE BESAR */
        .qr-section {
            position: absolute;
            bottom: 50px;
            right: 25px;
            background: var(--white);
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 2px solid var(--ivory);
            z-index: 2;
        }

        .footer-bar {
            margin-top: auto;
            background-color: var(--maroon);
            padding: 12px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer-bar span {
            color: var(--ivory);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 4px;
        }

        .btn-download {
            margin-top: 25px;
            background-color: var(--navy-deep);
            color: var(--white);
            padding: 14px 40px;
            border-radius: 50px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(20, 26, 69, 0.3);
            transition: 0.3s;
        }

        .btn-download:hover {
            background-color: var(--maroon);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div style="display: flex; flex-direction: column; align-items: center;">
    <div class="card">
        <div class="top-bar"></div>
        
        <div class="header">
            <div class="logo-box">
                <img src="{{ asset('unib.jpg') }}" alt="Logo" style="width: 100%;">
            </div>
            <div class="header-text">
                <h1>Perpustakaan Informatika</h1>
                <p>FAKULTAS TEKNIK - UNIVERSITAS BENGKULU</p>
            </div>
        </div>

        <div class="content">
            <div class="photo-area">
                <div class="photo-frame">
                    <img src="{{ asset('storage/foto/'.$user->foto) }}" alt="Foto">
                </div>
            </div>

            <div class="details">
                <div class="label">Nama Lengkap</div>
                <div class="value">{{ $user->nama }}</div>
                
                <div class="label">Nomor Pokok Mahasiswa</div>
                <div class="value" style="font-family: monospace; font-size: 18px;">{{ $user->npm }}</div>
            </div>
        </div>

        <div class="qr-section">
            {!! QrCode::size(90)->margin(1)->generate($user->npm) !!}
        </div>

        <div class="footer-bar">
            <span>MEMBER CARD DIGITAL</span>
        </div>
    </div>

    <button id="save-png" class="btn-download">SIMPAN KE GALERI (.PNG)</button>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.getElementById('save-png').addEventListener('click', function () {
        const card = document.querySelector('.card');
        html2canvas(card, {
            scale: 3, // Agar gambar HD saat disimpan
            useCORS: true,
            allowTaint: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'MemberCard_{{ $user->npm }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
</script>

</body>
</html>