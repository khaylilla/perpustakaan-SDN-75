<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* 🌄 Background gambar full screen */
   body {
      font-family: 'Poppins', sans-serif;
      background: url('{{ asset('FT.jpg') }}') center center / cover no-repeat fixed;
      margin: 0;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }


    /* 🕶️ Overlay gelap agar teks tetap terbaca */
   body::before {
      content: "";
      position: fixed; /* <- ubah jadi fixed supaya menempel di viewport */
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.5); /* overlay hitam 50% */
      z-index: 0;
      pointer-events: none; /* biar overlay nggak ganggu klik */
    }


    /* 🧊 Kontainer profil dengan efek blur (frosted glass) */
    .profile-container {
      position: relative;
      z-index: 1;
      max-width: 700px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 35px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      color: #fff;
    }

    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      border: 3px solid #f7931e;
    }

    label {
      font-weight: 500;
      color: #fff;
    }

    input.form-control {
      background-color: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 10px;
    }

    .btn-primary {
      background-color: #f7931e;
      border: none;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #d77e12;
    }

    h3 {
      color: #fff;
      font-weight: 600;
    }

    hr {
      border-color: rgba(255,255,255,0.4);
    }
  </style>
</head>
<body>

<div class="profile-container text-center">
  <h3 class="mb-4">Profil Pengguna</h3>

  {{-- ✅ Form Update Profil --}}
  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf

    @if($user->foto)
    <img src="{{ asset('storage/foto/' . $user->foto) }}?v={{ time() }}"
     alt="Foto Profil"
     class="profile-img">
    @else
      <img src="{{ asset('default.jpg') }}" alt="Foto Default" class="profile-img">
    @endif


    <div class="mb-3 text-start">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
    </div>

    {{-- SISWA: NISN, Asal Sekolah, Kelas --}}
    @if($loginAs === 'siswa')
      <div class="mb-3 text-start">
        <label>NISN</label>
        <input type="text" name="nisn" class="form-control" value="{{ $user->nisn }}" required>
      </div>

      <div class="mb-3 text-start">
        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" class="form-control" value="{{ $user->asal_sekolah ?? '' }}">
      </div>

      <div class="mb-3 text-start">
        <label>Kelas</label>
        <input type="text" name="kelas" class="form-control" value="{{ $user->kelas ?? '' }}">
      </div>

    {{-- GURU: NIP, Email, Alamat, No HP, Tanggal Lahir --}}
    @elseif($loginAs === 'guru')
      <div class="mb-3 text-start">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control" value="{{ $user->nip }}" required>
      </div>

      <div class="mb-3 text-start">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
      </div>

      <div class="mb-3 text-start">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
      </div>

      <div class="mb-3 text-start">
        <label>No HP</label>
        <input type="text" name="nohp" class="form-control" value="{{ $user->nohp ?? '' }}">
      </div>

      <div class="mb-3 text-start">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
      </div>

    {{-- UMUM: Email, Alamat, No HP, Tanggal Lahir --}}
    @elseif($loginAs === 'umum')
      <div class="mb-3 text-start">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
      </div>

      <div class="mb-3 text-start">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
      </div>

      <div class="mb-3 text-start">
        <label>No HP</label>
        <input type="text" name="nohp" class="form-control" value="{{ $user->nohp ?? '' }}">
      </div>

      <div class="mb-3 text-start">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
      </div>
    @endif

    <div class="mb-3 text-start">
      <label>Foto Profil</label>
      <input type="file" name="foto" class="form-control" id="fotoInput" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">Simpan Perubahan</button>
  </form>

  <hr class="my-4">

  <a href="{{ route('home') }}" class="btn btn-light w-100">Home</a>
</div>

{{-- ✅ SweetAlert --}}
@if(session('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '{{ session('success') }}',
  confirmButtonColor: '#f7931e'
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Gagal!',
  text: '{{ session('error') }}',
  confirmButtonColor: '#f7931e'
});
</script>
@endif

</body>
</html>
