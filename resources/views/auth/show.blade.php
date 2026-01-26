@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --primary-gold: #ffb84d;
        --accent-orange: #f7931e;
        --glass-bg: rgba(15, 23, 42, 0.7);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-light: #f8fafc;
        --text-muted: #94a3b8;
    }

    body {
        background: url('{{ asset('FT.jpg') }}') center/cover no-repeat fixed;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-light);
        margin: 0;
        overflow-x: hidden;
    }

    /* Overlay Sinematik */
    .overlay-vignette {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(16, 53, 109, 0.4) 0%, rgba(5, 15, 29, 0.9) 100%);
        z-index: -1;
    }

    .detail-page {
        min-height: 100vh;
        padding: 100px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Glass Card Utama */
    .book-card {
        background: var(--glass-bg);
        backdrop-filter: blur(25px) saturate(180%);
        -webkit-backdrop-filter: blur(25px) saturate(180%);
        border: 1px solid var(--glass-border);
        border-radius: 40px;
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        max-width: 1200px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 50px 100px rgba(0,0,0,0.6);
    }

    /* Bagian Cover Visual */
    .book-cover-section {
        background: rgba(0, 0, 0, 0.2);
        padding: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cover-slideshow {
        position: relative;
        width: 300px;
        height: 420px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .cover-slideshow img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        opacity: 0;
        transform: scale(1.1);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cover-slideshow img.active {
        opacity: 1;
        transform: scale(1);
    }

    /* Label Stok / Status */
    .status-badge {
        position: absolute;
        top: 30px;
        left: 30px;
        background: linear-gradient(45deg, var(--primary-gold), var(--accent-orange));
        color: #000;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Detail Informasi */
    .book-info-section {
        padding: 60px;
    }

    .book-info-section h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 3.5rem;
        line-height: 1.1;
        margin-bottom: 10px;
        background: linear-gradient(to bottom, #fff 60%, var(--primary-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .author-name {
        font-size: 1.2rem;
        color: var(--primary-gold);
        margin-bottom: 40px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Grid Spesifikasi */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .spec-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .spec-item label {
        display: block;
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 5px;
        letter-spacing: 1px;
    }

    .spec-item span {
        font-weight: 600;
        font-size: 15px;
    }

    /* Deskripsi Area */
    .description-box {
        margin-top: 30px;
    }

    .description-box h3 {
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: #fff;
    }

    .description-text {
        color: var(--text-muted);
        line-height: 1.8;
        font-size: 0.95rem;
    }

    /* Tombol-tombol */
    .action-group {
        display: flex;
        gap: 15px;
        margin-top: 40px;
    }

    .btn-custom {
        padding: 14px 28px;
        border-radius: 15px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        border: 1px solid var(--glass-border);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-5px);
        color: #fff;
    }

    .btn-download {
        background: linear-gradient(45deg, var(--primary-gold), var(--accent-orange));
        color: #000;
    }

    .btn-download:hover {
        box-shadow: 0 10px 20px rgba(247, 147, 30, 0.3);
        transform: translateY(-3px);
    }

    @media (max-width: 992px) {
        .book-card { grid-template-columns: 1fr; }
        .book-info-section { padding: 40px; }
        .book-info-section h1 { font-size: 2.5rem; }
    }
</style>

<div class="overlay-vignette"></div>

<div class="detail-page">
    <div class="book-card" data-aos="zoom-in">
        
        <div class="book-cover-section">
            <span class="status-badge">Tersedia: {{ $book->jumlah }} Buku</span>
            
            <div class="cover-slideshow">
                @php $covers = json_decode($book->cover, true); @endphp
                @if($covers && count($covers) > 0)
                    @foreach($covers as $index => $cover)
                        <img src="{{ asset('storage/' . $cover) }}" 
                             class="{{ $index === 0 ? 'active' : '' }}" 
                             alt="Cover">
                    @endforeach
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="active" alt="No Cover">
                @endif
            </div>
        </div>

        <div class="book-info-section">
            <div data-aos="fade-up" data-aos-delay="200">
                <h1>{{ $book->judul }}</h1>
                <div class="author-name">
                    <i class="bi bi-person-circle text-warning"></i> 
                    {{ $book->penulis ?? 'Penulis Anonim' }}
                </div>

                <div class="specs-grid">
                    <div class="spec-item">
                        <label>Penerbit</label>
                        <span>{{ $book->penerbit ?? '-' }}</span>
                    </div>
                    <div class="spec-item">
                        <label>Kategori</label>
                        <span>{{ $book->kategori ?? 'Umum' }}</span>
                    </div>
                    <div class="spec-item">
                        <label>Tahun</label>
                        <span>{{ $book->tahun_terbit ?? '-' }}</span>
                    </div>
                    <div class="spec-item">
                        <label>Kode Rak</label>
                        <span>{{ $book->rak ?? '-' }}</span>
                    </div>
                </div>

                <div class="description-box">
                    <h3>Sinopsis / Deskripsi</h3>
                    <div class="description-text">
                        {!! nl2br(e($book->deskripsi)) !!}
                    </div>
                </div>

                <div class="action-group">
                    <a href="{{ route('buku.index') }}" class="btn-custom btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    
                    @if($book->ebook)
                        @php $ebookUrl = strpos($book->ebook, 'http') === 0 ? $book->ebook : asset('storage/' . $book->ebook); @endphp
                        <a href="{{ $ebookUrl }}" target="_blank" class="btn-custom btn-download">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Baca E-Book
                        </a>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    // Inisialisasi AOS (Animasi on Scroll)
    AOS.init({ duration: 1000, once: true });

    // Slideshow Logic yang lebih halus
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.cover-slideshow img');
        if (slides.length > 1) {
            let current = 0;
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 4000);
        }
    });
</script>
@endsection