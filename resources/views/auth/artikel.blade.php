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

  body {
    background-color: var(--navy-dark);
    color: #f8fafc;
    font-family: var(--font-body);
  }

  .bg-ornament {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at 50% 0%, rgba(124, 23, 13, 0.15), transparent 50%);
    z-index: -1;
  }

  /* === HERO SECTION === */
  .hero-section {
    padding: 100px 20px 60px;
    text-align: center;
    position: relative;
  }

  .hero-section h1 {
    font-family: var(--font-heading);
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    margin-bottom: 15px;
    background: linear-gradient(to right, #fff, var(--accent-gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .hero-section p {
    font-size: 1.1rem;
    color: #94a3b8;
    max-width: 600px;
    margin: 0 auto;
  }

  /* === CONTAINER === */
  .artikel-container {
    max-width: 1000px;
    margin: 0 auto 100px;
    padding: 0 20px;
  }

  /* === SEARCH BAR === */
  .search-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 50px;
  }

  .search-box {
    display: flex;
    align-items: center;
    background: var(--navy-light);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 8px 10px 8px 25px;
    width: 100%;
    max-width: 600px;
    transition: 0.3s;
  }

  .search-box:focus-within {
    border-color: var(--accent-gold);
    box-shadow: 0 0 20px rgba(247, 147, 30, 0.15);
  }

  .search-box input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    color: #fff;
    padding: 12px 0;
    font-size: 1rem;
  }

  .search-box button {
    background: var(--accent-gold);
    border: none;
    color: var(--navy-dark);
    width: 45px;
    height: 45px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.3s;
    font-size: 1.2rem;
  }

  .search-box button:hover {
    background: #fff;
    transform: scale(1.05);
  }

  /* === ARTIKEL LIST === */
  .artikel-list {
    display: grid;
    gap: 25px;
  }

  .artikel-item {
    background: var(--glass-white);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    padding: 35px;
    transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    overflow: hidden;
  }

  .artikel-item::before {
    content: "";
    position: absolute;
    top: 0; left: 0; width: 4px; height: 100%;
    background: var(--accent-maroon);
    opacity: 0;
    transition: 0.3s;
  }

  .artikel-item:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(247, 147, 30, 0.3);
  }

  .artikel-item:hover::before {
    opacity: 1;
  }

  .artikel-item h5 {
    font-family: var(--font-heading);
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
    line-height: 1.3;
  }

  .artikel-item p {
    font-size: 1rem;
    color: #94a3b8;
    margin-bottom: 25px;
    line-height: 1.7;
  }

  .btn-read {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(247, 147, 30, 0.1);
    color: var(--accent-gold);
    font-weight: 600;
    padding: 10px 24px;
    border-radius: 12px;
    text-decoration: none;
    transition: 0.3s;
    border: 1px solid rgba(247, 147, 30, 0.2);
  }

  .btn-read:hover {
    background: var(--accent-gold);
    color: var(--navy-dark);
    transform: translateX(5px);
  }

  /* === EMPTY STATE === */
  .empty-state {
    text-align: center;
    padding: 60px;
    background: var(--glass-white);
    border-radius: 30px;
    border: 1px dashed rgba(255, 255, 255, 0.1);
  }

  @media (max-width: 768px) {
    .artikel-item { padding: 25px; }
    .hero-section { padding-top: 80px; }
  }
</style>

<div class="bg-ornament"></div>

{{-- === HERO SECTION === --}}
<section class="hero-section" data-aos="fade-up">
  <h1>Wawasan & Inovasi</h1>
  <p>Jelajahi kumpulan artikel edukatif mengenai perkembangan teknologi dan riset terbaru di perpustakaan kami.</p>
</section>

{{-- === MAIN CONTENT === --}}
<div class="artikel-container">
  
  {{-- SEARCH --}}
  <form action="{{ route('auth.artikel') }}" method="GET" class="search-wrapper" data-aos="fade-up" data-aos-delay="100">
    <div class="search-box">
      <input type="text" name="keyword" placeholder="Cari judul artikel..." value="{{ request('keyword') }}">
      <button type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  {{-- ARTIKEL LIST --}}
  @if($artikels->count())
    <div class="artikel-list">
      @foreach($artikels as $index => $artikel)
      <div class="artikel-item" data-aos="fade-up" data-aos-delay="{{ 150 + ($index * 50) }}">
        <h5>{{ $artikel->judul }}</h5>
        <p>{{ Str::limit($artikel->deskripsi, 250) }}</p>
        <a href="{{ $artikel->link }}" target="_blank" class="btn-read">
          Baca Selengkapnya <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      @endforeach
    </div>

    {{-- Tambahan: Pagination jika diperlukan --}}
    <div class="mt-5 d-flex justify-content-center">
        {{-- $artikels->links() --}}
    </div>

  @else
    <div class="empty-state" data-aos="zoom-in">
      <i class="bi bi-journal-x" style="font-size: 3rem; color: #475569;"></i>
      <h5 class="mt-3 text-secondary">Maaf, artikel tidak ditemukan.</h5>
      <p class="small text-muted">Coba gunakan kata kunci yang berbeda atau kembali lagi nanti.</p>
    </div>
  @endif
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>
@endsection