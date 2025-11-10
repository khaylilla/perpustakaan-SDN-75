<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Sistem Informasi Perpustakaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
      position: relative;
    }

    /* Background gambar FT */
    .background-img {
      position: absolute;
      inset: 0;
      background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
      opacity: 0.6;
      z-index: 0;
    }

    /* Segitiga oranye dari kiri */
    .triangle-left {
      position: absolute;
      top: 0;
      left: 0;
      width: 70%;
      height: 100vh;
      background-color: #f7931e;
      clip-path: polygon(0 0, 65% 0, 85% 100%, 0 100%);
      z-index: 1;
    }

    /* Kotak form login */
    .login-box {
      position: relative;
      z-index: 2;
      background: white;
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 0 25px rgba(0,0,0,0.2);
      width: 360px;
      text-align: center;
    }

    .login-box img {
      width: 100px;
      margin-bottom: 10px;
    }

    .login-box h3 {
      color: #1b2a6e;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .login-box p {
      font-size: 14px;
      color: #333;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .btn-login {
      background-color: #1b2a6e;
      color: white;
      border: none;
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-login:hover {
      background-color: #142257;
    }

    .text-center small a {
      color: #1b2a6e;
      font-weight: 600;
      text-decoration: none;
    }

    .forgot-password {
      text-align: right;
      font-size: 14px;
      margin-bottom: 15px;
    }

    .forgot-password a {
      color: #1b2a6e;
      text-decoration: none;
      font-weight: 500;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .triangle-left {
        width: 100%;
        clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
      }

      .login-box {
        width: 90%;
        padding: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="background-img"></div>
  <div class="triangle-left"></div>

  <div class="login-box">
    <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
    <h3>LOGIN</h3>
    <p>Isi NPM dan Password dibawah</p>

    <form method="POST" action="{{ route('login.submit') }}">
      @csrf
      <input type="text" name="npm" class="form-control" placeholder="NPM" required value="{{ old('npm') }}">
      <input type="password" name="password" class="form-control" placeholder="Password" required>

      @error('npm')
        <div class="text-danger small mb-2">{{ $message }}</div>
      @enderror

      <div class="forgot-password">
        <a href="{{ route('password.request') }}">Forgot password?</a>
      </div>

      <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="text-center mt-3">
      <small>Tidak punya akun? <a href="{{ url('/signin') }}">Sign up</a></small>
    </div>
  </div>
</body>
</html>
