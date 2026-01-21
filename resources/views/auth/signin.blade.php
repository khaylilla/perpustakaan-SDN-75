<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Perpustakaan SDN 75</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #1b2a6e;
            --accent: #f7931e;
            --glass: rgba(255, 255, 255, 0.88);
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== BACKGROUND CREATIVE ===== */
        .bg-wallpaper {
            position: fixed;
            inset: 0;
            background: url('{{ asset("FT.jpg") }}') center/cover no-repeat;
            z-index: -2;
            transform: scale(1.1);
            filter: brightness(0.6);
            animation: zoomBg 20s infinite alternate;
        }

        @keyframes zoomBg {
            from { transform: scale(1); }
            to { transform: scale(1.15); }
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(27, 42, 110, 0.7), rgba(0, 0, 0, 0.5));
            z-index: -1;
        }

        /* ===== GLASS CARD ===== */
        .card-signup {
            width: 100%;
            max-width: 480px;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 40px;
            margin: 40px 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            background: white;
            width: 65px;
            height: 65px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .logo-box:hover { transform: scale(1.05); }

        h4 { font-weight: 800; color: var(--primary); letter-spacing: -0.5px; }

        /* ===== ROLE TOGGLE ===== */
        .role-toggle {
            display: flex;
            background: rgba(0,0,0,0.06);
            padding: 5px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .role-toggle input { display: none; }
        
        .role-toggle label {
            flex: 1;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--primary);
            border-radius: 12px;
            transition: var(--transition);
        }

        .role-toggle input:checked + label {
            background: var(--primary);
            color: white;
        }

        /* ===== FORM STYLE ===== */
        label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            margin-left: 2px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
        }

        .form-control {
            border-radius: 12px;
            padding: 11px 16px;
            border: 1.5px solid transparent;
            background: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .form-control:focus {
            background: white;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(247, 147, 30, 0.15);
        }

        /* PERBAIKAN IKON MATA */
        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary);
            opacity: 0.5;
            z-index: 5;
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .form-section { display: none; }
        .visible { display: block !important; animation: fadeIn 0.4s ease forwards; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .upload-box {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 8px;
            background: rgba(255,255,255,0.5);
        }

        .btn-signup {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            margin-top: 15px;
            transition: var(--transition);
        }

        .btn-signup:hover {
            background: #142257;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(27, 42, 110, 0.2);
        }

        hr { margin: 25px 0; opacity: 0.1; }
        a { color: var(--accent); font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>

    <div class="bg-wallpaper"></div>
    <div class="bg-overlay"></div>

    <div class="card-signup">
        <div class="text-center mb-4">
            <div class="logo-box">
                <img src="{{ asset('unib.jpg') }}" width="40" alt="Logo">
            </div>
            <h4>Daftar Akun</h4>
            <p class="text-muted small">Silakan lengkapi data diri Anda</p>
        </div>

        <form action="{{ route('signin.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="role-toggle">
                <input type="radio" name="role" id="guru" value="guru" checked onclick="updateFields()">
                <label for="guru">GURU</label>
                <input type="radio" name="role" id="siswa" value="siswa" onclick="updateFields()">
                <label for="siswa">SISWA</label>
                <input type="radio" name="role" id="umum" value="umum" onclick="updateFields()">
                <label for="umum">UMUM</label>
            </div>

            <div id="section-email" class="form-section">
                <label>Alamat Email</label>
                <input type="email" name="email" class="form-control mb-3" placeholder="email@contoh.com">
            </div>

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap">
            </div>

            <div id="section-nip" class="form-section">
                <label>NIP</label>
                <input type="text" name="nip" class="form-control mb-3" placeholder="Masukkan NIP">
            </div>

            <div id="section-nis" class="form-section">
                <label>NISN (Nomor Induk Siswa Nasional)</label>
                <input type="text" name="nisn" class="form-control mb-3" placeholder="Masukkan NISN">
            </div>

            <div id="section-sekolah" class="form-section mb-3">
                <div class="row g-2">
                    <div class="col-8">
                        <label>Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" class="form-control" placeholder="Nama Sekolah">
                    </div>
                    <div class="col-4">
                        <label>Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="X-A">
                    </div>
                </div>
            </div>

            <div id="section-alamat" class="form-section mb-3">
                <label>Alamat Domisili</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat Lengkap">
            </div>

            <div id="section-biodata" class="form-section mb-3">
                <div class="row g-2">
                    <div class="col-6">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control">
                    </div>
                    <div class="col-6">
                        <label>No. HP</label>
                        <input type="text" name="nohp" class="form-control" placeholder="0812...">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label>Foto Profil</label>
                <div class="upload-box">
                    <input type="file" name="foto" class="form-control form-control-sm" accept="image/*">
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label>Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Minimal 8 karakter">
                    <span class="toggle-password" onclick="togglePassword('password')">
                        <i class="fa fa-eye" id="password-icon"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <label>Konfirmasi Sandi</label>
                <div class="input-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Ulangi sandi">
                    <span class="toggle-password" onclick="togglePassword('password_confirmation')">
                        <i class="fa fa-eye" id="password_confirmation-icon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn-signup">Daftar Sekarang</button>

            <div class="text-center mt-4">
                <span class="text-muted small">Sudah punya akun? <a href="{{ url('/login') }}">Masuk</a></span>
            </div>
        </form>
    </div>

    <script>
        function updateFields() {
            const role = document.querySelector('input[name="role"]:checked').value;
            
            const sections = {
                email: document.getElementById('section-email'),
                nip: document.getElementById('section-nip'),
                nis: document.getElementById('section-nis'),
                sekolah: document.getElementById('section-sekolah'),
                alamat: document.getElementById('section-alamat'),
                biodata: document.getElementById('section-biodata')
            };

            // Reset semua section
            Object.values(sections).forEach(s => s.classList.remove('visible'));

            // Logika tampilan berdasarkan role
            if (role === 'siswa') {
                sections.nis.classList.add('visible');
                sections.sekolah.classList.add('visible');
            } else if (role === 'umum') {
                sections.email.classList.add('visible');
                sections.alamat.classList.add('visible');
                sections.biodata.classList.add('visible');
            } else if (role === 'guru') {
                sections.email.classList.add('visible');
                sections.nip.classList.add('visible');
                sections.alamat.classList.add('visible');
                sections.biodata.classList.add('visible');
            }
        }

        // Inisialisasi awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', updateFields);

        // Fungsi toggle password
        function togglePassword(id){
            const input = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>