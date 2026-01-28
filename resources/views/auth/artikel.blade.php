@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
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
  }

  body { 
    background-color: var(--pure-white); 
    color: var(--text-main); 
    font-family: var(--font-body); 
    overflow-x: hidden;
  }

  /* ANIMASI BACKGROUND BERGERAK */
  .bg-animated {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    z-index: -1; background: #fff; overflow: hidden;
  }
  .bubble {
    position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.15; animation: float 20s infinite alternate;
  }
  .bubble-1 { width: 400px; height: 400px; background: var(--primary-blue); top: -100px; right: -100px; }
  .bubble-2 { width: 300px; height: 300px; background: var(--accent-red); bottom: -50px; left: -50px; animation-delay: -5s; }
  .bubble-3 { width: 250px; height: 250px; background: var(--deep-navy); top: 40%; left: 20%; animation-delay: -10s; }

  @keyframes float {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(50px, 100px) scale(1.2); }
  }

  /* HERO SECTION (DINAIKKAN MENDEKAT NAVBAR) */
  .hero-section { 
    padding: 40px 20px 30px; /* Jarak atas diperkecil dari 100px ke 40px */
    text-align: center; 
  }
  .hero-section h1 { 
    font-family: var(--font-heading); font-size: clamp(2.5rem, 5vw, 3.8rem); font-weight: 800; 
    background: linear-gradient(135deg, var(--deep-navy) 30%, var(--primary-blue) 60%, var(--accent-red) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    line-height: 1.2;
    filter: drop-shadow(0 10px 15px rgba(2, 31, 75, 0.1));
  }
  .hero-section p { color: var(--text-muted); font-weight: 500; max-width: 600px; margin: 10px auto 0; }

  .artikel-container { max-width: 1100px; margin: 0 auto 100px; padding: 0 20px; }

  /* SEARCH BAR (MODERN POP) */
  .search-wrapper { display: flex; justify-content: center; margin-bottom: 50px; }
  .search-box { 
    display: flex; align-items: center; background: var(--pure-white); 
    border: 1px solid #e2e8f0; border-radius: 24px; 
    padding: 10px 12px 10px 30px; width: 100%; max-width: 650px; 
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .search-box:focus-within { 
    border-color: var(--primary-blue); 
    box-shadow: 0 20px 40px rgba(10, 88, 202, 0.15);
    transform: translateY(-5px);
  }
  .search-box input { flex: 1; background: transparent; border: none; outline: none; color: var(--text-main); font-weight: 500; }
  .search-box button { 
    background: var(--deep-navy); border: none; color: white; 
    width: 48px; height: 48px; border-radius: 18px; transition: 0.3s; 
  }
  .search-box button:hover { background: var(--accent-red); transform: scale(1.1) rotate(5deg); }

  /* ARTIKEL CARD - POP UP & GLOW */
  .artikel-item {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 35px;
    padding: 22px;
    margin-bottom: 40px;
    transition: all 0.5s cubic-bezier(0.2, 1, 0.22, 1);
    display: flex; gap: 35px; align-items: center;
    position: relative;
    box-shadow: 0 10px 20px rgba(0,0,0,0.02);
  }

  .artikel-item:hover { 
    transform: translateY(-15px) scale(1.02); 
    box-shadow: 0 40px 80px rgba(2, 31, 75, 0.12);
    border-color: rgba(10, 88, 202, 0.3);
    background: var(--pure-white);
  }

  .artikel-img-wrapper {
    width: 340px; height: 230px; flex-shrink: 0; border-radius: 28px; 
    overflow: hidden; position: relative; background: #f8fafc;
  }
  .artikel-img-wrapper::after {
    content: ''; position: absolute; top: 0; left: -150%; width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: 0.8s;
  }
  .artikel-item:hover .artikel-img-wrapper::after { left: 150%; }

  .artikel-img-wrapper img { width: 100%; height: 100%; object-fit: cover; transition: 1s cubic-bezier(0.2, 1, 0.22, 1); }
  .artikel-item:hover .artikel-img-wrapper img { transform: scale(1.12); }

  .artikel-content { flex: 1; padding-right: 15px; }
  
  .badge-kat { 
    background: #f1f5f9; color: var(--deep-navy); 
    padding: 7px 18px; border-radius: 12px; font-size: 11px; font-weight: 800; 
    text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
    display: inline-block; margin-bottom: 15px;
  }
  .artikel-item:hover .badge-kat { background: var(--primary-blue); color: white; box-shadow: 0 8px 15px rgba(10, 88, 202, 0.2); }

  .artikel-content h5 { 
    font-family: var(--font-heading); font-size: 1.8rem; color: var(--deep-navy); 
    margin: 0 0 15px; line-height: 1.3; font-weight: 700; transition: 0.3s;
  }
  .artikel-item:hover .artikel-content h5 { color: var(--primary-blue); }
  
  .artikel-content p { color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin-bottom: 30px; }

  .btn-read {
    display: inline-flex; align-items: center; gap: 12px; color: var(--deep-navy); 
    font-weight: 800; text-decoration: none; transition: 0.3s;
    font-size: 0.95rem; padding: 10px 20px; border-radius: 15px; background: #f8fafc;
  }
  .btn-read i { 
    transition: 0.4s; color: white; font-size: 1.1rem; 
    background: var(--deep-navy); width: 30px; height: 30px; 
    display: flex; align-items: center; justify-content: center; border-radius: 50%;
  }
  .btn-read:hover { background: var(--deep-navy); color: white; }
  .btn-read:hover i { transform: translateX(5px); background: var(--accent-red); box-shadow: 0 0 15px var(--accent-red); }

  .content-link { position: relative; z-index: 3; text-decoration: none; }

  @media (max-width: 992px) {
    .artikel-item { flex-direction: column; align-items: flex-start; padding: 18px; }
    .artikel-img-wrapper { width: 100%; height: 260px; }
  }
</style>

<div class="bg-animated">
  <div class="bubble bubble-1"></div>
  <div class="bubble bubble-2"></div>
  <div class="bubble bubble-3"></div>
</div>

<section class="hero-section" data-aos="zoom-out">
  <h1 data-aos="fade-up">Wawasan & Inovasi</h1>
  <p data-aos="fade-up" data-aos-delay="100">Jelajahi berita dan artikel terbaru dari Fakultas Teknik UNIB.</p>
</section>

<div class="artikel-container">
  <form action="{{ route('auth.artikel') }}" method="GET" class="search-wrapper" data-aos="fade-up">
    <div class="search-box">
      <input type="text" name="keyword" placeholder="Cari artikel..." value="{{ request('keyword') }}">
      <button type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  @if($artikels->count())
    <div class="artikel-list">
      @foreach($artikels as $artikel)
      <div class="artikel-item" data-aos="fade-up">
        
        <a href="{{ route('artikel.show', $artikel->id) }}" class="content-link">
            <div class="artikel-img-wrapper shadow-sm">
              @if($artikel->foto)
                <img src="{{ asset('storage/' . $artikel->foto) }}" alt="{{ $artikel->judul }}">
              @else
                <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                  <i class="bi bi-image fs-1 opacity-25"></i>
                </div>
              @endif
            </div>
        </a>

        <div class="artikel-content">
          <span class="badge-kat">{{ $artikel->kategori }}</span>
          
          <a href="{{ route('artikel.show', $artikel->id) }}" class="content-link text-decoration-none">
            <h5>{{ $artikel->judul }}</h5>
          </a>

          <p>{{ Str::limit($artikel->isi, 160) }}</p>
          
          <a href="{{ route('artikel.show', $artikel->id) }}" class="btn-read content-link">
            Baca Selengkapnya <i class="bi bi-arrow-right-short"></i>
          </a>
        </div>
      </div>
      @endforeach
    </div>
  @else
    <div class="text-center py-5 rounded-5 shadow-sm" style="background: rgba(255,255,255,0.9); border: 2px dashed #e2e8f0;">
      <i class="bi bi-journal-x fs-1 text-muted opacity-50"></i>
      <h5 class="mt-3 fw-bold">Artikel tidak ditemukan</h5>
    </div>
  @endif
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true, offset: 100 });</script>
@endsection