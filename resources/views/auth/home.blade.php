@extends('layouts.app')
@section('title','Beranda')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
/* ============================================================= */
/* GLOBAL STYLING */
/* ============================================================= */
:root {
    --c-navy: #001F54;
    --c-orange: #f7931e;
    --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f8fafc;
    color: #334155;
}

/* Mengurangi jarak antar section agar tidak terlalu jauh */
section { padding: 40px 0; }
.container { max-width: 1240px; }

/* ============================================================= */
/* HERO SLIDER SECTION */
/* ============================================================= */
.hero-slider {
    position: relative;
    height: 550px;
    width: 100%;
    overflow: hidden;
}
.hero-slider img {
    width: 100%; height: 100%;
    object-fit: cover;
    filter: brightness(0.45);
}
.hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(0,31,84,0.3), rgba(0,31,84,0.7));
    z-index: 1;
}
.hero-content {
    position: absolute; top: 50%; left: 10%;
    transform: translateY(-50%);
    z-index: 2; color: white; max-width: 750px;
}
.hero-content h1 { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 20px; }
.hero-badge {
    display: inline-block; padding: 6px 14px;
    background: var(--c-orange); border-radius: 50px;
    font-weight: 700; font-size: 0.75rem;
    text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;
}

/* ============================================================= */
/* CARD & SLIDER STYLING */
/* ============================================================= */
.section-title { font-size: 24px; font-weight: 800; color: var(--c-navy); margin-bottom: 5px; }
.section-header { margin-bottom: 25px; }

/* Koleksi Card */
.koleksi-card {
    background: white; border-radius: 20px; overflow: hidden;
    transition: var(--transition); border: 1px solid rgba(0,0,0,0.05); height: 100%;
}
.koleksi-img-wrapper { height: 260px; overflow: hidden; }
.koleksi-img { width: 100%; height: 100%; object-fit: cover; transition: var(--transition); }
.koleksi-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }

/* Favorite Card */
.favorite-item-card {
    background: white; border-radius: 20px; padding: 25px;
    transition: var(--transition); border: 1.5px solid transparent;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}
.favorite-item-card:hover { border-color: var(--c-orange); transform: translateY(-5px); }

/* Article Card */
.article-card {
    background: white; border-radius: 20px; border: none;
    transition: var(--transition); box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

.rank-badge {
    width: 35px; height: 35px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.9rem;
}

.text-truncate-2 {
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}

/* Button */
.btn-primary-modern {
    background: var(--c-navy); color: white; padding: 12px 28px;
    border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-block;
}
.btn-primary-modern:hover { background: var(--c-orange); color: white; }
</style>

<div class="hero-slider swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="{{ asset('FT.jpg') }}" alt="Gedung Teknik">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge">Digital Library</span>
                <h1>Eksplorasi Ilmu Pengetahuan Teknik</h1>
                <p class="mb-4 opacity-75">Akses ribuan referensi buku, jurnal, dan karya ilmiah terbaik dari Fakultas Teknik UNIB.</p>
                <a href="{{ route('buku.index') }}" class="btn-primary-modern">Mulai Membaca</a>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1600" alt="Interior">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge">Resource Hub</span>
                <h1>Dukung Riset & Inovasi Mahasiswa</h1>
                <p class="mb-4 opacity-75">Fasilitas literasi lengkap untuk mendukung proyek masa depan Anda.</p>
                <a href="#koleksi" class="btn-primary-modern">Lihat Koleksi</a>
            </div>
        </div>
    </div>
    <div class="swiper-pagination"></div>
</div>

<section id="koleksi">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-end">
            <div>
                <h2 class="section-title">Koleksi Terbaru</h2>
                <p class="text-muted small m-0">Buku terbaru yang siap dipinjam.</p>
            </div>
            <a href="{{ route('buku.index') }}" class="fw-bold text-decoration-none" style="color:var(--c-orange);">Lihat Semua →</a>
        </div>

        <div class="swiper koleksi-swiper">
            <div class="swiper-wrapper">
                @foreach($books as $book)
                @php
                    $covers = json_decode($book->cover, true);
                    $title = $book->title ?? $book->judul ?? 'Untitled';
                @endphp
                <div class="swiper-slide" style="width: 260px;">
                    <div class="koleksi-card shadow-sm">
                        <div class="koleksi-img-wrapper">
                            @if($covers && count($covers) > 0)
                                <img src="{{ asset('storage/'.$covers[0]) }}" class="koleksi-img">
                            @else
                                <img src="https://via.placeholder.com/300x400" class="koleksi-img">
                            @endif
                        </div>
                        <div class="koleksi-body p-3">
                            <small class="fw-bold text-uppercase" style="color:var(--c-orange); font-size: 10px;">{{ $book->kategori->name ?? 'Umum' }}</small>
                            <h6 class="koleksi-title mt-1 mb-2 fw-bold text-truncate-2" style="font-size: 14px; height: 40px; color: var(--c-navy);">{{ $title }}</h6>
                            <p class="text-muted small mb-3">{{ Str::limit($book->author ?? $book->penulis, 20) }}</p>
                            <a href="{{ route('buku.show', $book->id) }}" class="btn btn-sm w-100" style="border: 1.5px solid var(--c-navy); color: var(--c-navy); font-weight: 700; border-radius: 10px;">Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section style="background: #f1f5f9;">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Buku Terpopuler</h2>
            <p class="text-muted small m-0">Pilihan favorit para pembaca saat ini.</p>
        </div>
        
        <div class="swiper populer-swiper">
            <div class="swiper-wrapper">
                @foreach($bukuFavorit as $index => $buku)
                <div class="swiper-slide" style="width: 300px;">
                    <div class="favorite-item-card h-100 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rank-badge" style="background: {{ $index == 0 ? '#FEF3C7' : ($index == 1 ? '#F1F5F9' : '#FFEDD5') }}; color: {{ $index == 0 ? '#92400E' : '#475569' }};">
                                {{ $index + 1 }}
                            </div>
                            <span class="badge rounded-pill bg-primary-subtle text-primary small">{{ $buku->total }}x Pinjam</span>
                        </div>
                        <h6 class="fw-bold text-truncate-2 mb-1" style="color: var(--c-navy); line-height: 1.4; height: 40px; font-size: 15px;">{{ $buku->judul_buku }}</h6>
                        <small class="text-muted">{{ $buku->penulis }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">Wawasan Terbaru</h2>
                <p class="text-muted small m-0">Berita dan tips terbaru seputar perpustakaan.</p>
            </div>
            <a href="{{ route('auth.artikel') }}" class="btn btn-sm fw-bold px-3 border-dark" style="border-radius: 10px;">Lihat Semua</a>
        </div>

        <div class="swiper artikel-swiper">
            <div class="swiper-wrapper">
                @foreach($artikels as $artikel)
                <div class="swiper-slide" style="width: 350px;">
                    <div class="card article-card h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="fa-regular fa-calendar text-muted small"></i>
                                <small class="text-muted fw-bold" style="font-size: 11px;">{{ $artikel->created_at->format('d M Y') }}</small>
                            </div>
                            <h5 class="fw-bold mb-3 text-truncate-2" style="color: var(--c-navy); font-size: 17px; line-height: 1.5; height: 50px;">{{ $artikel->judul }}</h5>
                            <p class="text-muted small mb-4">
                                {{ Str::limit(strip_tags($artikel->deskripsi), 100) }}
                            </p>
                            <a href="{{ route('auth.artikel') }}" class="text-decoration-none fw-bold" style="color: var(--c-orange); font-size: 14px;">
                                Selengkapnya <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Hero Slider
    new Swiper(".hero-slider", {
        loop: true, effect: "fade", autoplay: { delay: 5000 },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    // Koleksi Slider
    new Swiper(".koleksi-swiper", { slidesPerView: "auto", spaceBetween: 20, freeMode: true });

    // Populer Slider
    new Swiper(".populer-swiper", { slidesPerView: "auto", spaceBetween: 20, freeMode: true });

    // Artikel Slider
    new Swiper(".artikel-swiper", { slidesPerView: "auto", spaceBetween: 20, freeMode: true });
</script>
@endsection