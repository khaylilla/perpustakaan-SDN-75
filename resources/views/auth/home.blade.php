@extends('layouts.app')
@section('title','Beranda')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
/* ============================================================= */
/* GLOBAL REFINEMENT (RED-BLUE-WHITE THEME) */
/* ============================================================= */
:root {
    --primary-blue: #0A58CA;
    --deep-navy: #021f4b;
    --accent-red: #d90429;
    --pure-white: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
    --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

body {
    font-family: var(--font-body);
    background-color: #f8fafc;
    color: var(--text-main);
}

section { padding: 50px 0; }

/* ANIMASI BACKGROUND BUBBLES */
.bg-animated {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    z-index: -1; background: #fff; overflow: hidden;
}
.bubble {
    position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.08; animation: float 20s infinite alternate;
}
.bubble-1 { width: 400px; height: 400px; background: var(--primary-blue); top: -100px; right: -100px; }
.bubble-2 { width: 300px; height: 300px; background: var(--accent-red); bottom: -50px; left: -50px; animation-delay: -5s; }

@keyframes float {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(50px, 100px) scale(1.1); }
}

/* ============================================================= */
/* HERO SLIDER */
/* ============================================================= */
.hero-slider {
    height: 550px;
    width: 100%;
    overflow: hidden;
}
.hero-slider img {
    width: 100%; height: 100%; object-fit: cover;
}
.hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to right, rgba(2, 31, 75, 0.85), rgba(2, 31, 75, 0.2));
    z-index: 1;
}
.hero-content {
    position: absolute; top: 50%; left: 10%;
    transform: translateY(-50%);
    z-index: 2; color: white; max-width: 700px;
}
.hero-content h1 { 
    font-family: var(--font-heading);
    font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 20px; 
}
.hero-badge {
    display: inline-block; padding: 6px 15px;
    background: var(--accent-red); border-radius: 50px;
    font-weight: 700; font-size: 0.75rem;
    text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;
}

/* ============================================================= */
/* CARD STYLING (MATCHING THE LIST PAGE) */
/* ============================================================= */
.section-title { 
    font-family: var(--font-heading);
    font-size: 2rem; font-weight: 800; 
    color: var(--deep-navy);
}

/* Small Book Card */
.book-card-sm {
    background: var(--pure-white);
    border: 1px solid #f1f5f9;
    border-radius: 24px;
    padding: 12px;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    display: block;
    height: 100%;
}
.book-card-sm:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(2, 31, 75, 0.1);
    border-color: var(--primary-blue);
}
.img-wrapper-sm {
    height: 220px;
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 12px;
    background: #f8fafc;
}
.img-wrapper-sm img {
    width: 100%; height: 100%; object-fit: cover;
}

.category-tag {
    font-size: 10px;
    font-weight: 800;
    color: var(--primary-blue);
    text-transform: uppercase;
}

/* Popular Card */
.popular-card {
    background: white;
    border-radius: 20px;
    padding: 18px;
    border: 1px solid #f1f5f9;
    transition: var(--transition);
    border-left: 4px solid var(--accent-red);
}
.popular-card:hover {
    border-color: var(--accent-red);
    transform: translateX(5px);
}

/* Article Card */
.article-card-sm {
    background: white;
    border-radius: 24px;
    border: 1px solid #f1f5f9;
    padding: 20px;
    height: 100%;
    transition: var(--transition);
}
.article-card-sm:hover {
    box-shadow: 0 15px 30px rgba(10, 88, 202, 0.08);
}

.btn-primary-custom {
    background: var(--primary-blue);
    color: white;
    padding: 12px 28px;
    border-radius: 14px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s;
}
.btn-primary-custom:hover {
    background: var(--deep-navy);
    color: white;
    transform: translateY(-3px);
}
</style>

<div class="bg-animated">
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
</div>

<div class="hero-slider swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="{{ asset('FT.jpg') }}" alt="Gedung Teknik">
            <div class="hero-overlay"></div>
            <div class="hero-content" data-aos="fade-right">
                <span class="hero-badge">Digital Repository</span>
                <h1>Eksplorasi Ilmu Teknik Terlengkap</h1>
                <p class="mb-4 opacity-75">Akses ribuan referensi buku, jurnal, dan karya ilmiah pilihan dari Fakultas Teknik UNIB.</p>
                <a href="{{ route('buku.index') }}" class="btn-primary-custom">Mulai Jelajah</a>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1600" alt="Interior">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge">Modern Library</span>
                <h1>Fasilitas Riset & Inovasi</h1>
                <p class="mb-4 opacity-75">Ruang baca yang nyaman dan koleksi digital yang selalu diperbarui untuk riset Anda.</p>
                <a href="#koleksi" class="btn-primary-custom">Lihat Koleksi</a>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<section id="koleksi">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title">Koleksi Terbaru</h2>
                <div style="width: 50px; height: 4px; background: var(--accent-red); border-radius: 2px;"></div>
            </div>
            <a href="{{ route('buku.index') }}" class="fw-bold text-decoration-none" style="color:var(--primary-blue);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="swiper koleksi-swiper">
            <div class="swiper-wrapper">
                @foreach($books as $book)
                @php $covers = json_decode($book->cover, true); @endphp
                <div class="swiper-slide" style="width: 230px;">
                    <a href="{{ route('buku.show', $book->id) }}" class="book-card-sm">
                        <div class="img-wrapper-sm">
                            @if($covers && count($covers) > 0)
                                <img src="{{ asset('storage/'.$covers[0]) }}" alt="Cover">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Cover">
                            @endif
                        </div>
                        <div class="p-1">
                            <span class="category-tag">{{ $book->kategori }}</span>
                            <h6 class="fw-bold mt-1 text-truncate-2" style="font-size: 14px; color: var(--deep-navy); height: 38px;">{{ $book->judul }}</h6>
                            <p class="text-muted small mb-0" style="font-size: 11px;"><i class="bi bi-person me-1"></i>{{ Str::limit($book->penulis, 18) }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section style="background: rgba(10, 88, 202, 0.03);">
    <div class="container">
        <div class="section-header mb-4 text-center">
            <h2 class="section-title">Buku Terpopuler</h2>
            <p class="text-muted">Paling sering dibaca dan dipinjam bulan ini.</p>
        </div>
        
        <div class="row g-3">
            @foreach($bukuFavorit->take(4) as $index => $buku)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="popular-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger rounded-circle" style="width: 25px; height: 25px; padding: 5px;">{{ $index + 1 }}</span>
                        <span class="text-muted" style="font-size: 10px;"><i class="bi bi-fire text-danger"></i> {{ $buku->total }}x</span>
                    </div>
                    <h6 class="fw-bold text-truncate-2 mb-1" style="font-size: 14px; color: var(--deep-navy);">{{ $buku->judul_buku }}</h6>
                    <small class="text-muted">{{ Str::limit($buku->penulis, 20) }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="section-title">Wawasan Terbaru</h2>
                <div style="width: 50px; height: 4px; background: var(--primary-blue); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($artikels->take(3) as $artikel)
            <div class="col-md-4" data-aos="zoom-in">
                <div class="article-card-sm">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-calendar3 text-danger small"></i>
                        <small class="text-muted fw-bold" style="font-size: 10px;">{{ $artikel->created_at->format('d M Y') }}</small>
                    </div>
                    <h5 class="fw-bold mb-2 text-truncate-2" style="font-size: 16px; color: var(--deep-navy);">{{ $artikel->judul }}</h5>
                    <p class="text-muted small mb-3">{{ Str::limit(strip_tags($artikel->deskripsi), 80) }}</p>
                    <a href="{{ route('auth.artikel') }}" class="text-decoration-none fw-bold small" style="color: var(--accent-red);">
                        Baca Artikel <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    new Swiper(".hero-slider", {
        loop: true, effect: "fade", autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    new Swiper(".koleksi-swiper", { 
        slidesPerView: "auto", 
        spaceBetween: 20, 
        freeMode: true,
        grabCursor: true
    });
</script>
@endsection