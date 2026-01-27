<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Perpustakaan SDN 75</title>
    
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

        /* ===== BACKGROUND CREATIVE (Identik dengan Login) ===== */
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
        .card-forgot {
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
            text-align: center;
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

        h4 { font-weight: 800; color: var(--primary); letter-spacing: -0.5px; margin-bottom: 10px; }
        .instruction { color: #666; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.5; }

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
            text-align: left;
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

        .btn-send {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 15px;
            transition: var(--transition);
            box-shadow: 0 10px 20px rgba(27, 42, 110, 0.2);
        }

        .btn-send:hover {
            background: #142257;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(27, 42, 110, 0.3);
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .back-link:hover { color: var(--accent); }
        .back-link i { margin-right: 5px; }

        /* Alert Style Custom */
        .alert-custom {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border-left: 4px solid #198754;
            text-align: left;
        }

        .error-msg {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-top: 8px;
            border-left: 4px solid #dc3545;
            text-align: left;
        }
    </style>
</head>
<body>

    <div class="bg-wallpaper"></div>
    <div class="bg-overlay"></div>

    <div class="card-forgot">
        <div class="logo-box">
            <img src="{{ asset('unib.jpg') }}" width="45" alt="Logo UNIB">
        </div>
        
        <h4>Lupa Password</h4>
        <p class="instruction">Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>

        @if (session('status'))
            <div class="alert-custom">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label>Alamat Email</label>
                <input type="email" name="email" class="form-control" 
                       placeholder="contoh@email.com" 
                       required value="{{ old('email') }}">
                
                @error('email')
                    <div class="error-msg">
                        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-send">
                <i class="fa-solid fa-paper-plane me-2"></i> Kirim Tautan Reset
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
        </a>
    </div>

</body>
</html>