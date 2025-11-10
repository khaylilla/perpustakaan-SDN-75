<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - Sistem Informasi Perpustakaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      overflow-y: auto;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      font-family: 'Poppins', sans-serif;
      position: relative;
    }

    .background-img {
      position: fixed;
      inset: 0;
      background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
      opacity: 0.6;
      z-index: 0;
    }

    .triangle-left {
      position: fixed;
      top: 0;
      left: 0;
      width: 70%;
      height: 100vh;
      background-color: #f7931e;
      clip-path: polygon(0 0, 65% 0, 85% 100%, 0 100%);
      z-index: 1;
    }

    .signin-box {
      position: relative;
      z-index: 2;
      background: white;
      padding: 60px 50px;
      border-radius: 15px;
      box-shadow: 0 0 25px rgba(0,0,0,0.2);
      width: 650px;
      text-align: center;
      margin: 60px 0;
    }

    .signin-box img {
      width: 100px;
      margin-bottom: 10px;
    }

    .signin-box h3 {
      color: #1b2a6e;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .signin-box p {
      font-size: 14px;
      color: #333;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 8px;
      margin-bottom: 12px;
    }

    .btn-signin {
      background-color: #1b2a6e;
      color: white;
      border: none;
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-signin:hover {
      background-color: #142257;
    }

    .upload-box {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 25px;
      text-align: center;
      background-color: #f9f9f9;
    }

    .upload-box input {
      display: block;
      margin: 0 auto;
    }

    .text-center small a {
      color: #1b2a6e;
      font-weight: 600;
      text-decoration: none;
    }

    @media (max-width: 768px) {
      .triangle-left {
        width: 100%;
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
      }

      .signin-box {
        width: 90%;
        padding: 30px;
      }
    }
  </style>
</head>

<body>
  <div class="background-img"></div>
  <div class="triangle-left"></div>

  <div class="signin-box">
    <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
    <h3>SIGN IN</h3>
    <p>Isi data dibawah ini</p>

   <form action="{{ route('signin.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf


      <input type="email" name="email" class="form-control" placeholder="Email" required>
      <input type="password" name="password" class="form-control" placeholder="Password" required>
      <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
      <input type="text" name="npm" class="form-control" placeholder="NPM" required>
      <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
      <input type="date" name="tgl_lahir" class="form-control" placeholder="Tanggal Lahir" required>
      <input type="text" name="nohp" class="form-control" placeholder="No. Hp" required>

      <div class="upload-box mb-3">
        <input type="file" name="foto" accept="image/*" required>
        <p class="text-muted mt-2">Upload Foto</p>
      </div>

      <button type="submit" class="btn-signin">Sign in</button>

      <div class="text-center mt-3">
        <small>Sudah punya akun? <a href="{{ url('/login') }}">Login</a></small>
      </div>
    </form>
  </div>

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
        window.location.href = "/card/{{ session('user_id') }}";
    }
});
</script>
  @endif
</body>
</html>