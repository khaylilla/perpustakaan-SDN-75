<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna | SDN 75</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-blue: #0f172a;
            --accent-red: #dc2626;
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            color: #fff;
        }

        /* 🌄 Dynamic Background */
        .bg-animation {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url('{{ asset('FT.jpg') }}') center center / cover no-repeat;
            z-index: -2;
            animation: slowZoom 20s infinite alternate;
        }

        @keyframes slowZoom {
            from { transform: scale(1); }
            to { transform: scale(1.15); }
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, rgba(15, 23, 42, 0.6) 0%, rgba(0, 0, 0, 0.9) 100%);
            z-index: -1;
        }

        /* 🔙 Floating Back Button */
        .btn-back-floating {
            position: fixed;
            top: 30px; left: 30px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            padding: 12px 24px;
            border-radius: 15px;
            color: #fff;
            text-decoration: none !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back-floating:hover {
            background: var(--accent-red);
            transform: translateX(-5px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        }

        /* 🧊 Profile Card */
        .profile-container {
            max-width: 700px;
            width: 95%;
            margin: 100px auto 50px;
            background: var(--glass-bg);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 35px;
            padding: 50px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 0.8s ease-out;
            position: relative;
        }

        .profile-container::after {
            content: '';
            position: absolute;
            top: 0; left: 50%; transform: translateX(-50%);
            width: 150px; height: 5px;
            background: var(--accent-red);
            border-radius: 0 0 10px 10px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 📸 Avatar Style */
        .profile-img-wrapper {
            position: relative;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 150px; height: 150px;
            border-radius: 30px;
            object-fit: cover;
            border: 3px solid var(--accent-red);
            padding: 5px;
            background: rgba(255,255,255,0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .profile-img-wrapper:hover .profile-img {
            transform: scale(1.05) rotate(-2deg);
            border-color: #fff;
        }

        /* 🖋️ Form Elements */
        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: #fff;
            padding: 14px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-red);
            box-shadow: 0 0 20px rgba(220, 38, 38, 0.15);
            color: #fff;
        }

        /* 🔘 Buttons */
        .btn-save {
            background: linear-gradient(135deg, var(--accent-red) 0%, #991b1b 100%);
            border: none;
            border-radius: 18px;
            padding: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            color: #fff;
            margin-top: 20px;
        }

        .btn-save:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(220, 38, 38, 0.4);
            filter: brightness(1.2);
        }

        input[type="file"] {
            padding: 10px;
            font-size: 0.8rem;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1) brightness(0.8);
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="bg-animation"></div>

<a href="{{ route('home') }}" class="btn-back-floating">
    <i class="fas fa-arrow-left me-2"></i> KEMBALI 
</a>

<div class="profile-container">
    <div class="text-center mb-5">
        <h2 class="fw-800 mb-1" style="letter-spacing: -1px;">DATA PROFIL</h2>
        <p class="text-white-50 small">Kelola informasi autentikasi dan data diri Anda</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="text-center">
            <div class="profile-img-wrapper">
                @if($user->foto)
                    <img src="{{ asset('storage/foto/' . $user->foto) }}?v={{ time() }}" alt="Foto" class="profile-img" id="previewImg">
                @else
                    <img src="{{ asset('default.jpg') }}" alt="Default" class="profile-img" id="previewImg">
                @endif
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label"><i class="fas fa-user me-2"></i>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
            </div>

            @if($loginAs === 'siswa')
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-id-card me-2"></i>NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ $user->nisn }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-graduation-cap me-2"></i>Kelas</label>
                    <input type="text" name="kelas" class="form-control" value="{{ $user->kelas ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label"><i class="fas fa-school me-2"></i>Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" class="form-control" value="{{ $user->asal_sekolah ?? '' }}">
                </div>

            @elseif($loginAs === 'guru' || $loginAs === 'umum')
                @if($loginAs === 'guru')
                <div class="col-md-12">
                    <label class="form-label"><i class="fas fa-id-badge me-2"></i>NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" required>
                </div>
                @endif
                
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><i class="fas fa-phone me-2"></i>No HP</label>
                    <input type="text" name="nohp" class="form-control" value="{{ $user->nohp ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label"><i class="fas fa-calendar-alt me-2"></i>Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label"><i class="fas fa-map-marker-alt me-2"></i>Alamat Lengkap</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
                </div>
            @endif

            <div class="col-md-12">
                <label class="form-label"><i class="fas fa-camera me-2"></i>Ganti Foto Profil</label>
                <input type="file" name="foto" class="form-control" id="fotoInput" accept="image/*">
            </div>
        </div>

        <button type="submit" class="btn btn-save w-100">
            <i class="fas fa-save me-2"></i> Perbarui Profil
        </button>
    </form>
</div>

<script>
    // Preview Gambar
    document.getElementById('fotoInput').onchange = evt => {
        const [file] = document.getElementById('fotoInput').files
        if (file) {
            document.getElementById('previewImg').src = URL.createObjectURL(file)
        }
    }

    // SweetAlerts
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#dc2626'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            background: '#0f172a',
            color: '#fff',
            confirmButtonColor: '#dc2626'
        });
    @endif
</script>

</body>
</html>