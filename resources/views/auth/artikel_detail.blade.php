@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --primary-blue: #0A58CA;
        --deep-navy: #021f4b;
        --accent-red: #d90429;
        --pure-white: #ffffff;
        --text-slate: #334155;
        --glass-white: rgba(255, 255, 255, 0.95);
    }

    body {
        /* Latar belakang dengan gradien biru tipis agar nuansa putih-merah menonjol */
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: var(--text-slate);
        font-family: 'Plus Jakarta Sans', sans-serif;
        min-height: 100vh;
    }

    .container-detail {
        max-width: 850px;
        margin: 60px auto 100px;
        padding: 40px;
        background: var(--glass-white);
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(2, 31, 75, 0.1);
        border: 1px solid rgba(10, 88, 202, 0.1);
        position: relative;
    }

    /* Garis dekoratif merah di paling atas kontainer */
    .container-detail::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 150px;
        height: 5px;
        background: var(--accent-red);
        border-radius: 0 0 10px 10px;
    }

    .nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-blue);
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 30px;
        transition: 0.3s;
    }
    .nav-back:hover { color: var(--accent-red); transform: translateX(-5px); }

    /* Header Section */
    .article-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .badge-category {
        display: inline-block;
        padding: 6px 18px;
        background: var(--accent-red);
        color: white;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        border-radius: 50px;
        letter-spacing: 1.2px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(217, 4, 41, 0.2);
    }

    .title-main {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 900;
        line-height: 1.2;
        /* Gradien Biru ke Merah sesuai permintaan */
        background: linear-gradient(135deg, var(--deep-navy) 30%, var(--accent-red) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 25px;
    }

    .meta-stack {
        display: flex;
        justify-content: center;
        gap: 25px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        padding-bottom: 25px;
        border-bottom: 2px dashed #e2e8f0;
    }

    .meta-item i { color: var(--primary-blue); }

    /* Media Image */
    .media-container {
        margin: 40px auto;
        max-width: 90%; 
        text-align: center;
    }

    .img-frame {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border: 4px solid white;
    }

    /* Content Area */
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: var(--text-slate);
        text-align: justify;
    }

    .article-subjudul {
        font-size: 1.4rem;
        color: var(--deep-navy);
        font-weight: 800;
        margin: 35px 0 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .article-subjudul::before {
        content: '';
        width: 4px;
        height: 24px;
        background: var(--accent-red);
        display: inline-block;
        border-radius: 2px;
    }

    /* Action Box (Source Link) */
    .action-box {
        margin-top: 60px;
        padding: 30px;
        background: linear-gradient(135deg, var(--deep-navy), var(--primary-blue));
        border-radius: 20px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .btn-action {
        background: white;
        color: var(--deep-navy);
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: 0.3s;
        border: 2px solid transparent;
    }
    .btn-action:hover { 
        background: var(--accent-red); 
        color: white;
        transform: translateY(-3px);
    }

    @media (max-width: 768px) {
        .container-detail { margin: 20px; padding: 25px; }
        .action-box { flex-direction: column; text-align: center; }
        .meta-stack { flex-direction: column; gap: 10px; border-bottom: none; }
    }
</style>

<div class="container-detail" data-aos="fade-up">
    <a href="{{ route('auth.artikel') }}" class="nav-back">
        <i class="bi bi-arrow-left"></i> KEMBALI KE BERITA
    </a>

    <header class="article-header">
        <span class="badge-category" data-aos="zoom-in">{{ $artikel->kategori }}</span>
        <h1 class="title-main">
            {{ $artikel->judul }}
        </h1>
        
        <div class="meta-stack">
            <div class="meta-item">
                <i class="bi bi-calendar3"></i>
                <span>{{ $artikel->created_at->format('d M Y') }}</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-clock"></i>
                <span>{{ $artikel->created_at->format('H:i') }} WIB</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-patch-check-fill"></i>
                <span>Terverifikasi</span>
            </div>
        </div>
    </header>

    @if($artikel->foto)
    <div class="media-container" data-aos="fade-up">
        <img src="{{ asset('storage/' . $artikel->foto) }}" class="img-frame" alt="Cover Artikel">
    </div>
    @endif

    @if($artikel->subjudul)
        <div class="article-subjudul" data-aos="fade-up">{{ $artikel->subjudul }}</div>
    @endif

    <article class="article-content" data-aos="fade-up">
        {!! nl2br(e($artikel->isi)) !!}
    </article>

    @if($artikel->link)
    <div class="action-box" data-aos="zoom-in">
        <div class="action-text">
            <h4 class="mb-1 fw-bold">Informasi Lebih Lanjut</h4>
            <p class="opacity-75 small mb-0">Klik tombol di samping untuk mengunjungi tautan referensi asli.</p>
        </div>
        <a href="{{ $artikel->link }}" target="_blank" class="btn-action">
            BACA SUMBER <i class="bi bi-box-arrow-up-right ms-1"></i>
        </a>
    </div>
    @endif
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });
</script>
@endsection