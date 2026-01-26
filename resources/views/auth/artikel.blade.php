@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
  :root {
    --navy-dark: #020617;
    --navy-light: #0f172a;
    --accent-gold: #f7931e;
    --accent-maroon: #7C170D;
    --glass-white: rgba(255, 255, 255, 0.03);
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
  }

  body { background-color: var(--navy-dark); color: #f8fafc; font-family: var(--font-body); }
  .bg-ornament { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 50% 0%, rgba(124, 23, 13, 0.15), transparent 50%); z-index: -1; }

  .hero-section { padding: 60px 20px 20px; text-align: center; }
  .hero-section h1 { 
    font-family: var(--font-heading); font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 800; 
    background: linear-gradient(to right, #fff, var(--accent-gold));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  }

  .artikel-container { max-width: 1100px; margin: 0 auto 100px; padding: 0 20px; }

  /* SEARCH BAR CUSTOM */
  .search-wrapper { display: flex; justify-content: center; margin-bottom: 50px; }
  .search-box { 
    display: flex; align-items: center; background: var(--navy-light); 
    border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; 
    padding: 8px 10px 8px 25px; width: 100%; max-width: 600px; 
  }
  .search-box input { flex: 1; background: transparent; border: none; outline: none; color: #fff; }
  .search-box button { background: var(--accent-gold); border: none; color: var(--navy-dark); width: 45px; height: 45px; border-radius: 15px; }

  /* ARTIKEL CARD REFINED */
  .artikel-item {
    background: var(--glass-white);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 28px;
    padding: 25px;
    margin-bottom: 30px;
    transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: flex;
    gap: 30px;
    align-items: center;
  }

  .artikel-item:hover { transform: translateY(-8px); border-color: var(--accent-gold); background: rgba(255, 255, 255, 0.07); }

  .artikel-img-wrapper {
    width: 320px; height: 210px; flex-shrink: 0; border-radius: 20px; 
    overflow: hidden; border: 1px solid rgba(255,255,255,0.1);
  }

  .artikel-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
  .artikel-item:hover .artikel-img-wrapper img { transform: scale(1.1); }

  .artikel-content { flex: 1; }
  .badge-kat { 
    background: rgba(247, 147, 30, 0.1); color: var(--accent-gold); 
    padding: 5px 15px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase;
  }
  .artikel-content h5 { font-family: var(--font-heading); font-size: 1.6rem; color: #fff; margin: 12px 0; line-height: 1.3; }
  .artikel-content p { color: #94a3b8; font-size: 0.95rem; line-height: 1.7; margin-bottom: 20px; }

  .btn-read {
    display: inline-flex; align-items: center; gap: 8px; color: var(--accent-gold); 
    font-weight: 700; text-decoration: none; transition: 0.3s;
  }
  .btn-read:hover { color: #fff; transform: translateX(5px); }

  @media (max-width: 992px) {
    .artikel-item { flex-direction: column; align-items: flex-start; }
    .artikel-img-wrapper { width: 100%; height: 250px; }
  }
</style>

<div class="bg-ornament"></div>

<section class="hero-section" data-aos="fade-down">
  <h1>Wawasan & Inovasi</h1>
  <p>Temukan artikel inspiratif, berita sekolah, dan panduan belajar terbaru.</p>
</section>

<div class="artikel-container">
  {{-- SEARCH --}}
  <form action="{{ route('auth.artikel') }}" method="GET" class="search-wrapper" data-aos="fade-up">
    <div class="search-box">
      <input type="text" name="keyword" placeholder="Cari judul artikel..." value="{{ request('keyword') }}">
      <button type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  {{-- ARTIKEL LIST --}}
  @if($artikels->count())
    <div class="artikel-list">
      @foreach($artikels as $index => $artikel)
      <div class="artikel-item" data-aos="fade-up" data-aos-delay="{{ 100 * ($loop->index + 1) }}">
        
        <div class="artikel-img-wrapper">
          @if($artikel->foto)
            <img src="{{ asset('storage/' . $artikel->foto) }}" alt="{{ $artikel->judul }}">
          @else
            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark text-muted">
              <i class="bi bi-image fs-1 mb-2"></i>
              <small>No Preview</small>
            </div>
          @endif
        </div>

        <div class="artikel-content">
          <span class="badge-kat">{{ $artikel->kategori }}</span>
          <h5>{{ $artikel->judul }}</h5>
          <p>{{ Str::limit($artikel->isi, 180) }}</p>
          <a href="{{ route('artikel.show', $artikel->id) }}" class="btn-read">
            Baca Selengkapnya <i class="bi bi-arrow-right-circle-fill"></i>
          </a>
        </div>

      </div>
      @endforeach
    </div>
  @else
    <div class="text-center py-5 border rounded-4" style="border: 1px dashed rgba(255,255,255,0.1) !important;">
      <i class="bi bi-search fs-1 text-muted"></i>
      <h5 class="mt-3 text-secondary">Artikel tidak ditemukan</h5>
      <p class="text-muted small">Coba kata kunci lain atau periksa kategori Anda.</p>
    </div>
  @endif
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init({ duration: 800, once: true });</script>
@endsection