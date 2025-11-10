<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absen Pengunjung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      height: 100vh;
      overflow: hidden;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
    }

    body::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.3);
      z-index: 0;
    }

    .container-absen {
      position: relative;
      z-index: 1;
      display: flex;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    .left-panel {
      background-color: #f7931e;
      color: white;
      padding: 30px 25px;
      width: 350px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border-top-left-radius: 15px;
      border-bottom-left-radius: 15px;
    }

    .left-panel img {
      width: 150px;
      height: auto;
      margin-bottom: 20px;
    }

    .left-panel h5 {
      font-weight: bold;
      color: #002366;
    }

    .right-panel {
      background-color: #e9e9e9;
      padding: 40px 30px;
      width: 320px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
    }

    .right-panel h4 {
      text-align: center;
      margin-bottom: 20px;
      color: #1b2a6e;
      font-weight: bold;
    }

    .form-control {
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .btn-kirim {
      background-color: #f7931e;
      color: white;
      border: none;
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      font-weight: 500;
    }

    .btn-kirim:hover {
      background-color: #d77e12;
    }

    .close-btn {
      position: absolute;
      top: 20px;
      right: 20px;
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

    @media (max-width: 768px) {
      .container-absen {
        flex-direction: column;
      }
      .left-panel, .right-panel {
        width: 100%;
        border-radius: 0;
      }
    }
  </style>
</head>
<body>

  {{-- ✅ Tombol silang kembali ke home --}}
  @if(Auth::check())
    <a href="{{ route('home') }}">
      <button type="button" class="close-btn">&times;</button>
    </a>
  @endif

  <div class="container-absen">
    <div class="left-panel text-center">
      <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
      <div>
        <p class="mb-1">Selamat datang..</p>
        <h5>Perpustakaan Fakultas Teknik,<br>Universitas Bengkulu</h5>
        <p>Mulailah pencarianmu sekarang, dan temukan sumber ilmu yang berharga.</p>
      </div>
    </div>

    <div class="right-panel">
      <h4>Absen Pengunjung</h4>
      <form method="POST" action="{{ route('absen.submit') }}">
        @csrf
        <input type="text" name="nama" class="form-control" placeholder="Nama" required>
        <input type="text" name="npm" class="form-control" placeholder="NPM" required>
        <input type="text" name="prodi" class="form-control" placeholder="Program Studi" required>
        <button type="submit" class="btn-kirim">Submit</button>
      </form>
    </div>
  </div>

  {{-- ✅ Script untuk notifikasi sukses --}}
  <!-- ✅ SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @if (session('success'))
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      confirmButtonText: 'OK',
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "{{ route('home') }}";
    }
});
</script>
  @endif

</body>
</html>
