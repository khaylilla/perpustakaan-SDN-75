<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota Perpustakaan SDN 75</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --maroon-dark: #641414; /* Warna bar bawah */
            --maroon-text: #7C170D; /* Warna teks utama */
            --navy: #1A2A4E; /* Warna teks sub-header & value */
            --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            position: relative;
            width: 550px; /* Ukuran proporsional sesuai gambar */
            height: 320px;
            background-color: var(--white);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
        }

        /* Latar belakang dengan gambar FT.jpg dan overlay terang */
        .card-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
            z-index: 0;
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.75); /* Overlay transparan sesuai gambar */
            z-index: 1;
        }

        .content-layer {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            padding: 25px 35px 10px 35px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-box {
            width: 65px;
            height: 65px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .header-text h1 {
            margin: 0;
            font-size: 22px;
            color: var(--maroon-text);
            font-weight: 900;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 14px;
            color: var(--navy);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Main Content Area */
        .main-body {
            display: flex;
            padding: 10px 35px;
            gap: 30px;
            flex-grow: 1;
            align-items: center;
        }

        .photo-frame {
            width: 120px;
            height: 150px;
            border-radius: 15px;
            background: #fff;
            border: 4px solid var(--white);
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-section {
            flex-grow: 1;
        }

        .info-item {
            margin-bottom: 12px;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #4b5563;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .value {
            font-size: 20px;
            font-weight: 900;
            color: var(--navy);
            text-transform: uppercase;
            line-height: 1.2;
        }

        /* Barcode di posisi kanan sesuai gambar */
        .barcode-box {
            background: #fff;
            padding: 12px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #f3f4f6;
        }

        /* Footer Strip Merah */
        .footer-strip {
            height: 15px;
            background-color: var(--maroon-dark);
            width: 100%;
        }

        .btn-download {
            margin-top: 30px;
            background-color: var(--navy);
            color: white;
            padding: 15px 35px;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-download:hover {
            transform: scale(1.05);
        }

        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: var(--navy);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background-color: var(--maroon-text);
            color: white;
            text-decoration: none;
        }

        @media print {
            .btn-download, .btn-back { display: none; }
        }
    </style>
</head>
<body>

<a href="{{ route('home') }}" class="btn-back">← Kembali ke Home</a>

<div style="display:flex; flex-direction:column; align-items:center; padding: 20px;">
    <div class="card">
        <div class="card-bg"></div>
        <div class="card-overlay"></div>

        <div class="content-layer">
            <div class="header">
                <div class="logo-box">
                    <img src="{{ asset('unib.jpg') }}" style="width:100%; height:auto;">
                </div>
                <div class="header-text">
                    <h1>Perpustakaan SDN 75</h1>
                    <p>SEKOLAH DASAR NEGERI 75</p>
                </div>
            </div>

            <div class="main-body">
                <div class="photo-frame">
                    <img src="{{ asset('storage/foto/'.$user->foto) }}" onerror="this.src='{{ asset('foto profil.png') }}'">
                </div>

                <div class="info-section">
                    <div class="info-item">
                        <div class="label">Nama Lengkap</div>
                        <div class="value">{{ $user->nama }}</div>
                    </div>

                    <div class="info-item">
                        @php
                            $identifier = 'ID';
                            $val = $user->id;
                            if($loginAs === 'siswa') { $identifier = 'NISN'; $val = $user->nisn; }
                            elseif($loginAs === 'guru') { $identifier = 'NIP'; $val = $user->nip; }
                            else { $identifier = 'Email'; $val = $user->email; }
                        @endphp
                        <div class="label">{{ $identifier }}</div>
                        <div class="value">{{ $val }}</div>
                    </div>
                </div>

                <div class="barcode-box">
                    @php
                        $barcode = new \Milon\Barcode\DNS1D();
                        $barcode->setStorPath(sys_get_temp_dir() . '/');
                        echo $barcode->getBarcodeHTML((string)$val, 'C128', 1.8, 50);
                    @endphp
                </div>
            </div>

            <div class="footer-strip"></div>
        </div>
    </div>

    <button id="save-png" class="btn-download">SIMPAN KE GALERI (.PNG)</button>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.getElementById('save-png').addEventListener('click', function () {
        html2canvas(document.querySelector('.card'), { 
            scale: 3,
            useCORS: true,
            allowTaint: true 
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'Kartu_Anggota_{{ $user->nama }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    });
</script>

</body>
</html>
