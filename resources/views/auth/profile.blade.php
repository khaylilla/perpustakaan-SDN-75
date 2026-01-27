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
      --primary-color: #f7931e;
      --primary-dark: #d77e12;
      --glass-bg: rgba(255, 255, 255, 0.12);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* 🌄 Background & Animasi Zoom-Out Lambat */
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow-x: hidden;
      background: #000;
    }

    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('{{ asset('FT.jpg') }}') center center / cover no-repeat;
      z-index: -2;
      animation: slowZoom 20s infinite alternate;
    }

    @keyframes slowZoom {
      from { transform: scale(1); }
      to { transform: scale(1.1); }
    }

    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%);
      z-index: -1;
    }

    /* 🔙 TOMBOL KEMBALI DI KIRI ATAS (BARU) */
    .btn-back-floating {
      position: fixed;
      top: 25px;
      left: 25px;
      z-index: 1000; /* Pastikan di atas elemen lain */
      background: var(--glass-bg);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid var(--glass-border);
      padding: 10px 20px;
      border-radius: 50px;
      color: #fff;
      text-decoration: none !important;
      font-weight: 600;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .btn-back-floating:hover {
      background: var(--primary-color);
      color: #fff;
      transform: translateX(5px); /* Efek geser sedikit saat hover */
      box-shadow: 0 10px 20px rgba(247, 147, 30, 0.3);
      border-color: var(--primary-color);
    }

    /* 🧊 Container Profil (Slide In Animation) */
    .profile-container {
      position: relative;
      max-width: 650px;
      width: 90%;
      /* Tambah margin top supaya tidak terlalu dekat dengan tombol kembali di layar kecil */
      margin: 80px auto 40px auto; 
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 30px;
      padding: 40px;
      box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
      color: #fff;
      animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* 📸 Foto Profil dengan Efek Glow & Zoom */
    .profile-img-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 25px;
    }

    .profile-img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--primary-color);
      transition: all 0.4s ease;
      box-shadow: 0 0 20px rgba(247, 147, 30, 0.3);
    }

    .profile-img-wrapper:hover .profile-img {
      transform: scale(1.05) rotate(3deg);
      box-shadow: 0 0 30px rgba(247, 147, 30, 0.6);
    }

    /* 🖋️ Form Styling */
    .form-label {
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-left: 5px;
      color: rgba(255,255,255,0.8);
    }

    .form-control {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      color: #fff;
      padding: 12px 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.2);
      border-color: var(--primary-color);
      box-shadow: 0 0 15px rgba(247, 147, 30, 0.2);
      color: #fff;
    }

    /* 🔘 Button Styling */
    .btn-save {
      background: linear-gradient(45deg, var(--primary-color), #ffb347);
      border: none;
      border-radius: 12px;
      padding: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      color: #fff;
    }

    .btn-save:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(247, 147, 30, 0.4);
      background: linear-gradient(45deg, #ffb347, var(--primary-color));
    }

    /* Customize Date Picker Icon */
    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }
  </style>
</head>
<body>

<div class="bg-animation"></div>

<a href="{{ route('home') }}" class="btn-back-floating">
  <i class="fas fa-arrow-left"></i> Kembali 
</a>

<div class="profile-container text-center">
  <div class="header-content mb-4">
    <h3 class="fw-bold">PROFIL PENGGUNA</h3>
    <p class="text-white-50 small">Kelola informasi data diri Anda di bawah ini</p>
  </div>

  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="profile-img-wrapper">
      @if($user->foto)
        <img src="{{ asset('storage/foto/' . $user->foto) }}?v={{ time() }}" alt="Foto" class="profile-img" id="previewImg">
      @else
        <img src="{{ asset('default.jpg') }}" alt="Default" class="profile-img" id="previewImg">
      @endif
    </div>

    <div class="row text-start">
      <div class="col-md-12 mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
      </div>

      @if($loginAs === 'siswa')
        <div class="col-md-6 mb-3">
          <label class="form-label">NISN</label>
          <input type="text" name="nisn" class="form-control" value="{{ $user->nisn }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Kelas</label>
          <input type="text" name="kelas" class="form-control" value="{{ $user->kelas ?? '' }}">
        </div>
        <div class="col-md-12 mb-3">
          <label class="form-label">Asal Sekolah</label>
          <input type="text" name="asal_sekolah" class="form-control" value="{{ $user->asal_sekolah ?? '' }}">
        </div>

      @elseif($loginAs === 'guru' || $loginAs === 'umum')
        @if($loginAs === 'guru')
        <div class="col-md-12 mb-3">
          <label class="form-label">NIP</label>
          <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" required>
        </div>
        @endif
        
        <div class="col-md-6 mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="nohp" class="form-control" value="{{ $user->nohp ?? '' }}">
        </div>
        <div class="col-md-12 mb-3">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
        </div>
        <div class="col-md-12 mb-3">
          <label class="form-label">Alamat</label>
          <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
        </div>
      @endif

      <div class="col-md-12 mb-4">
        <label class="form-label">Ganti Foto Profil</label>
        <input type="file" name="foto" class="form-control" id="fotoInput" accept="image/*">
      </div>
    </div>

    <button type="submit" class="btn btn-save w-100 mb-3">Simpan Perubahan</button>
  </form>

  </div>

<script>
  // Preview Gambar sebelum upload
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
      background: '#222',
      color: '#fff',
      confirmButtonColor: '#f7931e'
    });
  @endif

  @if(session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      background: '#222',
      color: '#fff',
      confirmButtonColor: '#d33'
    });
  @endif
</script>

</body>
</html>