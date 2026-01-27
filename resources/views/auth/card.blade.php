<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Anggota Perpustakaan SDN 75</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --maroon: #7C170D;
            --navy: #1A2A4E;
            --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* TOMBOL KEMBALI DI KIRI ATAS */
        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px; /* Diubah kembali ke kiri */
            background-color: var(--white);
            color: var(--navy);
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            z-index: 1000;
            border: 1px solid #e2e8f0;
        }

        .btn-back:hover {
            background-color: var(--navy);
            color: white;
            transform: translateY(-2px);
        }

        .card {
            position: relative;
            width: 550px; 
            height: 320px;
            background-color: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
        }

        /* Latar Belakang Gambar Jelas */
        .card-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
            z-index: 0;
            opacity: 0.8; 
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.75) 100%);
            z-index: 1;
        }

        .content-layer {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .header {
            padding: 20px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .logo-box {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }

        .header-text h1 {
            margin: 0;
            font-size: 18px;
            color: var(--maroon);
            font-weight: 900;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 0;
            font-size: 11px;
            color: var(--navy);
            font-weight: 700;
            letter-spacing: 1px;
        }

        .main-body {
            display: flex;
            padding: 20px 30px;
            gap: 25px;
            flex-grow: 1;
            align-items: center;
        }

        .photo-frame {
            width: 95px;
            height: 125px;
            border-radius: 12px;
            background: #fff;
            padding: 4px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Grid Informasi (Sampingan) */
        .info-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 0; /* Penting agar text wrapping bekerja */
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr; 
            gap: 10px;
        }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #475569;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .value {
            font-size: 13px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            word-wrap: break-word;
            text-transform: uppercase;
        }

        .barcode-section {
            margin-top: 5px;
            background: white;
            padding: 8px 12px;
            border-radius: 10px;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #f1f5f9;
            width: fit-content;
        }

        .footer-strip {
            height: 10px;
            background: linear-gradient(90deg, var(--maroon), #4a0d08);
            width: 100%;
        }

        .btn-download {
            margin-top: 25px;
            background-color: var(--navy);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(26,42,78,0.2);
            transition: 0.3s;
        }

        .btn-download:hover { transform: translateY(-2px); }

        @media print { .btn-back, .btn-download { display: none; } }
    </style>
</head>
<body>

<a href="{{ route('home') }}" class="btn-back">← Kembali</a>

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
                    <p>KARTU DIGITAL ANGGOTA</p>
                </div>
            </div>

            <div class="main-body">
                <div class="photo-frame">
                    <img src="{{ asset('storage/foto/'.$user->foto) }}" onerror="this.src='{{ asset('foto profil.png') }}'">
                </div>

                <div class="info-container">
                    <div class="info-grid">
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

                    <div class="barcode-section">
                        @php
                            $barcode = new \Milon\Barcode\DNS1D();
                            $barcode->setStorPath(sys_get_temp_dir() . '/');
                            echo $barcode->getBarcodeHTML((string)$val, 'C128', 1.4, 45);
                        @endphp
                        <div style="font-size: 8px; font-weight: 800; color: var(--maroon); margin-top: 5px; letter-spacing: 2px;">
                            VALID MEMBER
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-strip"></div>
        </div>
    </div>

    <button id="save-png" class="btn-download">UNDUH KARTU (.PNG)</button>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.getElementById('save-png').addEventListener('click', function () {
        html2canvas(document.querySelector('.card'), { 
            scale: 4, 
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