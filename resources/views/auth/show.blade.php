@extends('layouts.app')

@section('title', 'Detail Buku - ' . $book->judul)

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --primary-blue: #0A58CA;
        --deep-navy: #021f4b;
        --accent-red: #d90429;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --glass-white: rgba(255, 255, 255, 0.92);
        --font-heading: 'Outfit', sans-serif;
        --font-body: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: var(--font-body);
        background: #000; /* Fallback */
        overflow-x: hidden;
    }

    /* --- BACKGROUND IMAGE WITH ZOOM ANIMATION --- */
    .bg-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -2;
        overflow: hidden;
    }

    .bg-image {
        width: 100%;
        height: 100%;
        background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
        animation: bg-zoom 20s infinite alternate ease-in-out;
    }

    @keyframes bg-zoom {
        from { transform: scale(1); }
        to { transform: scale(1.15); }
    }

    /* Overlay Sinematik untuk memperjelas Card */
    .bg-overlay {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(2, 31, 75, 0.3) 0%, rgba(0, 0, 0, 0.8) 100%);
        z-index: -1;
    }

    .detail-page {
        min-height: 100vh;
        padding: 80px 20px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Glass Card Utama */
    .book-card {
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 40px;
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        max-width: 1100px;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 50px 100px rgba(0, 0, 0, 0.5);
    }

    /* Bagian Cover Visual */
    .book-cover-section {
        background: #f1f5f9;
        padding: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cover-slideshow {
        position: relative;
        width: 280px;
        height: 400px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(2, 31, 75, 0.2);
        background: #fff;
    }

    .cover-slideshow img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        opacity: 0;
        transform: scale(1.05);
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
        background: var(--deep-navy);
        color: #fff;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        z-index: 10;
    }

    /* Detail Informasi */
    .book-info-section {
        padding: 60px;
        background: #fff;
    }

    .book-info-section h1 {
        font-family: var(--font-heading);
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 10px;
        color: var(--deep-navy);
    }

    .author-name {
        font-size: 1.1rem;
        color: var(--primary-blue);
        margin-bottom: 35px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Grid Spesifikasi */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 15px;
        margin-bottom: 35px;
    }

    .spec-item {
        background: #f8fafc;
        padding: 15px;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
    }

    .spec-item label {
        display: block;
        font-size: 10px;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 4px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .spec-item span {
        font-weight: 700;
        font-size: 14px;
        color: var(--deep-navy);
    }

    /* Deskripsi Area */
    .description-box h3 {
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 12px;
        color: var(--deep-navy);
    }

    .description-text {
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 0.95rem;
    }

    /* Tombol-tombol */
    .action-group {
        display: flex;
        gap: 12px;
        margin-top: 40px;
    }

    .btn-custom {
        padding: 12px 25px;
        border-radius: 16px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn-back {
        background: #f1f5f9;
        color: var(--deep-navy);
        border: 1px solid #e2e8f0;
    }

    .btn-back:hover {
        background: #e2e8f0;
        transform: translateX(-5px);
    }

    .btn-download {
        background: var(--primary-blue);
        color: #fff;
    }

    .btn-download:hover {
        background: var(--deep-navy);
        box-shadow: 0 10px 20px rgba(10, 88, 202, 0.3);
        transform: translateY(-3px);
        color: #fff;
    }

    @media (max-width: 992px) {
        .book-card { grid-template-columns: 1fr; border-radius: 30px; }
        .book-info-section { padding: 40px; }
        .book-info-section h1 { font-size: 2.2rem; }
        .book-cover-section { padding: 40px; }
    }
</style>

<div class="bg-wrapper">
    <div class="bg-image"></div>
</div>
<div class="bg-overlay"></div>

<div class="detail-page">
    <div class="book-card" data-aos="zoom-in">
        
        <div class="book-cover-section">
            <span class="status-badge">
                <i class="bi bi-stack me-1"></i> Stok: {{ $book->jumlah }}
            </span>
            
            <div class="cover-slideshow">
                @php $covers = json_decode($book->cover, true); @endphp
                @if($covers && count($covers) > 0)
                    @foreach($covers as $index => $cover)
                        <img src="{{ asset('storage/' . $cover) }}" 
                             class="{{ $index === 0 ? 'active' : '' }}" 
                             alt="Cover Buku">
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
                    <i class="bi bi-pen-fill"></i> 
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
                    <h3>Sinopsis</h3>
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
                            <i class="bi bi-journal-bookmark-fill"></i> Baca Digital
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
    AOS.init({ duration: 1000, once: true });

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