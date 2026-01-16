<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Perpustakaan Digital</title>
    
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

        /* ===== BACKGROUND CREATIVE (Sama dengan Sign Up) ===== */
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

        /* Overlay Gradasi agar teks terbaca */
        .bg-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(27, 42, 110, 0.7), rgba(0, 0, 0, 0.5));
            z-index: -1;
        }

        /* ===== GLASS CARD LOGIN ===== */
        .card-login {
            width: 100%;
            max-width: 420px;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 45px;
            margin: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.4);
            animation: slideUp 0.8s ease-out;
            position: relative;
            z-index: 10;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-box {
            background: white;
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .logo-box:hover { transform: scale(1.05) rotate(5deg); }

        h4 { font-weight: 800; color: var(--primary); letter-spacing: -0.5px; margin-bottom: 5px; }

        /* ===== FORM STYLE ===== */
        label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            margin-left: 2px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: block;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1.5px solid transparent;
            background: rgba(255, 255, 255, 0.8);
            transition: var(--transition);
            font-size: 0.95rem;
            color: #333;
        }

        .form-control:focus {
            background: white;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(247, 147, 30, 0.15);
            transform: translateY(-2px);
        }

        /* INPUT WRAPPER UNTUK IKON MATA */
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
            transition: 0.3s;
        }

        .toggle-password:hover { opacity: 1; color: var(--accent); }

        .btn-login {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 10px;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(27, 42, 110, 0.2);
        }

        .btn-login:hover {
            background: #142257;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(27, 42, 110, 0.3);
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.8rem;
            color: var(--primary);
            text-decoration: none;
            margin-top: 8px;
            font-weight: 600;
            transition: 0.3s;
        }

        .forgot-link:hover { color: var(--accent); }

        .divider {
            margin: 30px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }

        a.signup-link { 
            color: var(--accent); 
            font-weight: 700; 
            text-decoration: none; 
            transition: 0.3s;
        }
        
        a.signup-link:hover { text-decoration: underline; }

        /* Error Message Style */
        .error-msg {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-bottom: 15px;
            border-left: 4px solid #dc3545;
        }

    </style>
</head>
<body>

    <div class="bg-wallpaper"></div>
    <div class="bg-overlay"></div>

    <div class="card-login">
        <div class="text-center mb-4">
            <div class="logo-box">
                <img src="{{ asset('unib.jpg') }}" width="45" alt="Logo UNIB">
            </div>
            <h4>Selamat Datang</h4>
            <p class="text-muted small">Masuk untuk mengakses perpustakaan</p>
        </div>

        @if($errors->has('identifier'))
            <div class="error-msg">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first('identifier') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div class="mb-3">
                <label>Email / NIS / NIP</label>
                <input type="text" name="identifier" class="form-control" 
                       placeholder="Masukkan kredensial Anda" 
                       required value="{{ old('identifier') }}">
            </div>

            <div class="mb-2">
                <label>Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" 
                           placeholder="••••••••" required>
                    <span class="toggle-password" onclick="togglePassword('password')">
                        <i class="fa-solid fa-eye" id="password-icon"></i>
                    </span>
                </div>
            </div>

            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>

            <button type="submit" class="btn-login">Masuk ke Akun</button>
        </form>

        <div class="divider"></div>

        <div class="text-center">
            <span class="text-muted small">Belum punya akun? 
                <a href="{{ url('/signin') }}" class="signup-link">Daftar Sekarang</a>
            </span>
        </div>
    </div>

    <script>
        function togglePassword(id) {
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