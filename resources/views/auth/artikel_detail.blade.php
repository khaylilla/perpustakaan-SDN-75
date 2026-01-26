@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --navy-dark: #020617;
        --navy-accent: #0f172a;
        --gold: #f7931e;
        --gold-glow: rgba(247, 147, 30, 0.2);
        --glass: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
    }

    body {
        background-color: var(--navy-dark);
        color: #e2e8f0;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* 1. Menaikkan Konten (Margin diperkecil dari 140px ke 80px) */
    .container-detail {
        max-width: 850px;
        margin: 80px auto 100px;
        padding: 0 25px;
        position: relative;
    }

    .nav-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--gold);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 30px;
        transition: 0.3s;
    }
    .nav-back:hover { opacity: 0.8; transform: translateX(-3px); }

    /* Header Section */
    .article-header {
        text-align: center; /* Membuat header lebih rapi di tengah */
        margin-bottom: 40px;
    }

    .badge-category {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(247, 147, 30, 0.1);
        border: 1px solid var(--gold);
        color: var(--gold);
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
        border-radius: 8px;
        letter-spacing: 1.5px;
        margin-bottom: 20px;
    }

    .title-main {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem); /* Ukuran sedikit dikecilkan agar lebih elegan */
        font-weight: 800;
        line-height: 1.2;
        color: #fff;
        margin-bottom: 25px;
    }

    .meta-stack {
        display: flex;
        justify-content: center;
        gap: 20px;
        color: #94a3b8;
        font-size: 13px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--glass-border);
    }

    .meta-item { display: flex; align-items: center; gap: 6px; }
    .meta-item i { color: var(--gold); }

    /* 2. Mengecilkan Gambar (Max-width 70% dan Max-height dikurangi) */
    .media-container {
        margin: 40px auto;
        max-width: 80%; /* Gambar tidak selebar kontainer teks */
        text-align: center;
    }

    .img-frame {
        width: 100%;
        max-height: 400px; /* Dibatasi agar tidak terlalu tinggi */
        object-fit: cover;
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    /* 3. Isi Konten Lebih Rapi */
    .article-content {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #cbd5e1;
        text-align: justify; /* Membuat teks rata kanan-kiri agar terlihat rapi */
    }

    .article-content p {
        margin-bottom: 25px;
    }

    /* Action Box */
    .action-box {
        margin-top: 60px;
        padding: 30px;
        background: var(--navy-accent);
        border-radius: 24px;
        border: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .action-text h4 { font-family: 'Outfit', sans-serif; color: #fff; margin-bottom: 5px; }
    .btn-action {
        background: var(--gold);
        color: var(--navy-dark);
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: 0.3s;
    }
    .btn-action:hover { transform: translateY(-3px); box-shadow: 0 10px 20px var(--gold-glow); }

    @media (max-width: 768px) {
        .media-container { max-width: 100%; }
        .action-box { flex-direction: column; text-align: center; }
        .meta-stack { flex-direction: column; gap: 10px; align-items: center; }
    }
</style>

<div class="container-detail">
    <a href="{{ route('auth.artikel') }}" class="nav-back" data-aos="fade-right">
        <i class="bi bi-arrow-left"></i> Kembali ke Eksplorasi
    </a>

    <header class="article-header">
        <div data-aos="fade-up">
            <span class="badge-category">{{ $artikel->kategori }}</span>
        </div>
        <h1 class="title-main" data-aos="fade-up" data-aos-delay="100">
            {{ $artikel->judul }}
        </h1>
        
        <div class="meta-stack" data-aos="fade-up" data-aos-delay="200">
            <div class="meta-item">
                <i class="bi bi-calendar-event"></i>
                <span>{{ $artikel->created_at->format('d M Y') }}</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-person-circle"></i>
                <span>Admin</span>
            </div>
            <div class="meta-item">
                <i class="bi bi-clock"></i>
                <span>5 Menit Baca</span>
            </div>
        </div>
    </header>

    @if($artikel->foto)
    <div class="media-container" data-aos="zoom-in">
        <img src="{{ asset('storage/' . $artikel->foto) }}" class="img-frame" alt="Cover Artikel">
    </div>
    @endif

    <article class="article-content" data-aos="fade-up">
        {!! nl2br(e($artikel->isi)) !!}
    </article>

    @if($artikel->link)
    <div class="action-box" data-aos="fade-up">
        <div class="action-text">
            <h4>Pelajari Lebih Dalam</h4>
            <p class="text-muted small mb-0">Temukan referensi tambahan melalui tautan eksternal ini.</p>
        </div>
        <a href="{{ $artikel->link }}" target="_blank" class="btn-action">
            Buka Sumber <i class="bi bi-box-arrow-up-right"></i>
        </a>
    </div>
    @endif
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endsection