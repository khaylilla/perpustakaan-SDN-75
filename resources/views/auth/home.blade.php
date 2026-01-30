@extends('layouts.app')
@section('title','Beranda')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
:root {
    --primary-blue: #0A58CA;
    --deep-navy: #021f4b;
    --accent-red: #d90429;
    --pure-white: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body { 
    font-family: var(--font-body); 
    background-color: #f8fafc; 
    color: var(--text-main); 
}

section { padding: 40px 0; }

/* ============================================================= */
/* 1. HERO SLIDER */
/* ============================================================= */
.hero-slider { height: 500px; width: 100%; overflow: hidden; position: relative; }
.hero-slider .swiper-slide { height: 500px; width: 100%; }
.hero-slider img { width: 100%; height: 100%; object-fit: cover; display: block; }

.hero-overlay { 
    position: absolute; inset: 0; 
    background: linear-gradient(90deg, rgba(2, 31, 75, 0.9) 0%, rgba(2, 31, 75, 0.3) 100%); 
    z-index: 1; 
}

.hero-content { 
    position: absolute; 
    top: 35%; 
    left: 8%; 
    transform: translateY(-35%); 
    z-index: 2; 
    color: white; 
    width: 85%; 
    max-width: 1000px; 
}

.hero-content h1 { 
    font-family: var(--font-heading); 
    font-size: clamp(2.2rem, 5vw, 3.8rem); 
    font-weight: 800; line-height: 1.1; margin-bottom: 15px; letter-spacing: -1px; 
}

.hero-badge { 
    display: inline-block; padding: 6px 16px; 
    background: var(--accent-red); border-radius: 50px; 
    font-weight: 700; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 15px; 
}

/* ============================================================= */
/* 2. KOLEKSI TERBARU (MODIFIKASI JARAK & FONT) */
/* ============================================================= */
#koleksi { 
    padding-bottom: 60px; /* JARAK DITAMBAH (Sebelumnya 10px) */
}

.book-card-sm { 
    background: white; border: 1px solid #f1f5f9; border-radius: 12px; padding: 12px; 
    transition: var(--transition); text-decoration: none; color: inherit; display: block; height: auto; 
}
.book-card-sm:hover { 
    transform: translateY(-5px); border-color: var(--primary-blue);
    box-shadow: 0 10px 20px rgba(2, 31, 75, 0.05);
}

.img-wrapper-sm { 
    height: 170px; /* Tinggi gambar sedikit ditambah agar proporsional */
    border-radius: 10px; overflow: hidden; margin-bottom: 12px; background: #f1f5f9; 
}
.img-wrapper-sm img { width: 100%; height: 100%; object-fit: cover; }

/* Class Baru untuk Judul Buku Lebih Besar */
.book-title-lg {
    font-size: 15px !important; /* UKURAN FONT DIPERBESAR (Sebelumnya 13px) */
    font-weight: 700;
    color: var(--deep-navy);
    height: 40px;
    line-height: 1.3;
    margin-top: 5px;
    margin-bottom: 2px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.category-tag { font-size: 10px; font-weight: 800; color: var(--primary-blue); text-transform: uppercase; }

/* ============================================================= */
/* OTHER SECTIONS */
/* ============================================================= */
.popular-card { 
    background: white; border-radius: 16px; padding: 15px; 
    border: 1px solid #f1f5f9; border-left: 4px solid var(--accent-red);
}
.article-card-sm { 
    background: white; border-radius: 20px; border: 1px solid #f1f5f9; 
    padding: 20px; transition: var(--transition); 
}

.btn-primary-custom {
    background: var(--primary-blue); color: white; padding: 12px 28px; 
    border-radius: 12px; font-weight: 700; text-decoration: none; 
    display: inline-block; transition: 0.3s;
}
.btn-primary-custom:hover { background: var(--deep-navy); transform: translateY(-2px); color: white; }

.section-title { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 800; color: var(--deep-navy); }
</style>

<div class="hero-slider swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img src="{{ asset('FT.jpg') }}" alt="Gedung Teknik">
            <div class="hero-overlay"></div>
            <div class="hero-content" data-aos="fade-up">
                <span class="hero-badge">Digital Repository</span>
                <h1>Eksplorasi Ilmu Terlengkap</h1>
                <p class="mb-4 opacity-75 d-none d-md-block">Akses ribuan referensi buku, jurnal, dan karya ilmiah pilihan dari SDN 75 Kota Bengkulu.</p>
                <a href="{{ route('buku.index') }}" class="btn-primary-custom">Mulai Jelajah <i class="bi bi-arrow-right ms-2"></i></a>
            </div>
        </div>
        <div class="swiper-slide">
            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1600" alt="Interior">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge">Modern Library</span>
                <h1>Fasilitas Riset & Inovasi</h1>
                <p class="mb-4 opacity-75 d-none d-md-block">Ruang baca yang nyaman dan koleksi digital yang selalu diperbarui untuk riset Anda.</p>
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
                <div style="width: 40px; height: 4px; background: var(--accent-red); border-radius: 2px;"></div>
            </div>
            <a href="{{ route('buku.index') }}" class="fw-bold text-decoration-none small" style="color:var(--primary-blue);">Lihat Semua</a>
        </div>

        <div class="swiper koleksi-swiper">
            <div class="swiper-wrapper">
                @foreach($books as $book)
                @php $covers = json_decode($book->cover, true); @endphp
                <div class="swiper-slide" style="width: 180px;"> 
                    <a href="{{ route('buku.show', $book->id) }}" class="book-card-sm">
                        <div class="img-wrapper-sm">
                            @if($covers && count($covers) > 0)
                                <img src="{{ asset('storage/'.$covers[0]) }}" alt="Cover">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Cover">
                            @endif
                        </div>
                        <div class="px-1">
                            <span class="category-tag">{{ $book->kategori }}</span>
                            <h6 class="book-title-lg">{{ $book->judul }}</h6>
                            <p class="text-muted small mb-0" style="font-size: 11px;">{{ Str::limit($book->penulis, 18) }}</p>
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
            <p class="text-muted small">Paling sering dibaca bulan ini.</p>
        </div>
        
        <div class="row g-3">
            @foreach($bukuFavorit->take(4) as $index => $buku)
            <div class="col-md-6 col-lg-3">
                <div class="popular-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-danger rounded-circle" style="width: 22px; height: 22px; padding: 4px; font-size: 10px;">{{ $index + 1 }}</span>
                        <span class="text-muted" style="font-size: 10px;"><i class="bi bi-fire text-danger"></i> {{ $buku->total }}x</span>
                    </div>
                    <h6 class="fw-bold text-truncate-2 mb-1" style="font-size: 14px; color: var(--deep-navy);">{{ $buku->judul_buku }}</h6>
                    <small class="text-muted" style="font-size: 11px;">{{ Str::limit($buku->penulis, 20) }}</small>
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
                <div style="width: 40px; height: 4px; background: var(--primary-blue); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($artikels->take(3) as $artikel)
            <div class="col-md-4">
                <div class="article-card-sm">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-calendar3 text-danger small"></i>
                        <small class="text-muted fw-bold" style="font-size: 10px;">{{ $artikel->created_at->format('d M Y') }}</small>
                    </div>
                    <h5 class="fw-bold mb-2 text-truncate-2" style="font-size: 15px; color: var(--deep-navy); height: 42px;">{{ $artikel->judul }}</h5>
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
        loop: true, 
        effect: "fade", 
        fadeEffect: { crossFade: true },
        autoplay: { delay: 5000, disableOnInteraction: false },
        pagination: { el: ".swiper-pagination", clickable: true },
    });

    new Swiper(".koleksi-swiper", { 
        slidesPerView: "auto", 
        spaceBetween: 15, /* Sedikit renggang */
        freeMode: true,
        grabCursor: true,
        autoHeight: true
    });
</script>
@endsection