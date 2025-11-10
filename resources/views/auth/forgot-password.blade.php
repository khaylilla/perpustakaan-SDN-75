<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupa Password - Sistem Informasi Perpustakaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
      font-family: 'Poppins', sans-serif;
      position: relative;
    }

    .overlay {
      position: absolute;
      inset: 0;
      background: rgba(255, 255, 255, 0.7);
    }

    .forgot-box {
      position: relative;
      z-index: 2;
      background: white;
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 0 25px rgba(0,0,0,0.2);
      width: 360px;
      text-align: center;
    }

    .forgot-box img {
      width: 90px;
      margin-bottom: 10px;
    }

    .btn-send {
      background-color: #1b2a6e;
      color: white;
      border: none;
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-send:hover {
      background-color: #142257;
    }

    a {
      color: #1b2a6e;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="overlay"></div>

  <div class="forgot-box">
    <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
    <h4 class="fw-bold text-dark">Lupa Password</h4>
    <p class="text-muted mb-4">Masukkan email akun kamu untuk menerima link reset password.</p>

    @if (session('status'))
      <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <input type="email" name="email" class="form-control mb-3" placeholder="Masukkan email kamu" required>

      @error('email')
        <div class="text-danger small mb-2">{{ $message }}</div>
      @enderror

      <button type="submit" class="btn-send">Kirim Link Reset</button>
    </form>

    <div class="mt-3">
      <small><a href="{{ route('login') }}">← Kembali ke Login</a></small>
    </div>
  </div>
</body>
</html>
