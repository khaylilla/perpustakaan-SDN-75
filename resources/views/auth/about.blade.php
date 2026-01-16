@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

<style>
  :root {
    --navy-dark: #020617;
    --navy-light: #1e293b;
    --accent-gold: #f7931e;
    --font-heading: 'Outfit', sans-serif;
    --font-body: 'Plus Jakarta Sans', sans-serif;
  }

  body {
    background-color: var(--navy-dark);
    color: #f8fafc;
    font-family: var(--font-body);
    overflow-x: hidden;
  }

  .bg-ornament {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at 80% 20%, rgba(124, 23, 13, 0.1), transparent 40%);
    z-index: -1;
  }

  /* ==== HERO SECTION ==== */
  .hero-wrapper {
    padding: 80px 0 40px;
  }

  .main-title {
    font-family: var(--font-heading);
    font-size: clamp(2.2rem, 6vw, 3.8rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -2px;
    margin-bottom: 15px;
  }

  .gradient-text {
    background: linear-gradient(to right, #ffffff, var(--accent-gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* ==== INFO CARDS (SEKARANG DI TENAH) ==== */
  .grid-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 60px; /* Jarak ke galeri di bawahnya */
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 25px;
    transition: 0.3s;
  }

  .glass-card:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(247, 147, 30, 0.3);
  }

  /* ==== GALLERY SECTION (SEKARANG DI BAWAH) ==== */
  .gallery-container {
    position: relative;
    padding-bottom: 100px;
    max-width: 1100px;
    margin: 0 auto;
  }

  .section-label {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
    color: var(--accent-gold);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.85rem;
  }

  .section-label::after {
    content: "";
    height: 1px;
    flex-grow: 1;
    background: linear-gradient(to right, var(--accent-gold), transparent);
    opacity: 0.3;
  }

  .gallery-item {
    width: 400px; 
    height: 260px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255,255,255,0.1);
    cursor: zoom-in;
    transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .gallery-item img {
    width: 100%; height: 100%; object-fit: cover;
  }

  .gallery-item:hover {
    border-color: var(--accent-gold);
    transform: scale(1.02);
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  }

  /* ==== NAV BUTTONS ==== */
  .nav-btn {
    position: absolute;
    top: 55%;
    transform: translateY(-50%);
    width: 45px;
    height: 45px;
    background: var(--accent-gold);
    color: var(--navy-dark);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: 0.3s;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  }

  .nav-btn:hover { background: #fff; transform: translateY(-50%) scale(1.1); }
  .prev-btn { left: -22px; }
  .next-btn { right: -22px; }

  @media(max-width: 1200px) {
    .prev-btn { left: 10px; }
    .next-btn { right: 10px; }
  }

  @media(max-width: 768px) {
    .grid-info { grid-template-columns: 1fr; }
    .gallery-item { width: 85vw; height: 220px; }
    .nav-btn { display: none; } /* Di HP biasanya lebih enak swipe saja */
  }
</style>

<div class="bg-ornament"></div>

<div class="container">
  <div class="hero-wrapper" data-aos="fade-up">
    <h1 class="main-title">Modernitas dalam <br><span class="gradient-text">Genggaman Literasi.</span></h1>
    <p class="text-secondary" style="font-size: 1.05rem; max-width: 600px; opacity: 0.8;">
      Pusat rujukan teknik digital yang menghubungkan tradisi akademik dengan teknologi masa depan.
    </p>
  </div>

  <div class="grid-info">
    <div class="glass-card" data-aos="fade-up" data-aos-delay="100">
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--accent-gold); font-size: 0.8rem;">Visi Utama</h6>
      <p class="text-secondary small m-0">Menjadi rujukan teknik terbaik dengan akses digital inklusif bagi sivitas akademika.</p>
    </div>
    <div class="glass-card" data-aos="fade-up" data-aos-delay="200">
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--accent-gold); font-size: 0.8rem;">Layanan Prima</h6>
      <p class="text-secondary small m-0">Sirkulasi buku dan akses database jurnal internasional secara real-time.</p>
    </div>
    <div class="glass-card" data-aos="fade-up" data-aos-delay="300">
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--accent-gold); font-size: 0.8rem;">Fasilitas</h6>
      <p class="text-secondary small m-0">Ruang kolaborasi nyaman untuk merangsang riset dan inovasi mahasiswa.</p>
    </div>
  </div>

  <div class="gallery-container" data-aos="fade-up">
    <div class="section-label">
      <i class="fas fa-camera-retro"></i> Galeri Perpustakaan
    </div>

    <button class="nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>

    <div class="swiper-landscape swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide" style="width: auto;">
          <a href="{{ asset('FT.jpg') }}" data-fancybox="gallery" data-caption="Gedung Fakultas Teknik">
            <div class="gallery-item"><img src="{{ asset('FT.jpg') }}"></div>
          </a>
        </div>
        <div class="swiper-slide" style="width: auto;">
          <a href="https://images.unsplash.com/photo-1541339907198-e08759dfc3ef?q=80&w=1470" data-fancybox="gallery" data-caption="Ruang Baca Modern">
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1541339907198-e08759dfc3ef?q=80&w=1470"></div>
          </a>
        </div>
        <div class="swiper-slide" style="width: auto;">
          <a href="https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=1430" data-fancybox="gallery" data-caption="Koleksi Buku Lengkap">
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1568667256549-094345857637?q=80&w=1430"></div>
          </a>
        </div>
        <div class="swiper-slide" style="width: auto;">
          <a href="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=1473" data-fancybox="gallery" data-caption="Area Diskusi Digital">
            <div class="gallery-item"><img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=1473"></div>
          </a>
        </div>
      </div>
    </div>

    <button class="nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
  </div>
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<script>
  AOS.init({ duration: 1000, once: true });

  new Swiper(".swiper-landscape", {
    slidesPerView: "auto",
    spaceBetween: 25,
    loop: true,
    speed: 700,
    navigation: {
      nextEl: ".next-btn",
      prevEl: ".prev-btn",
    },
  });

  Fancybox.bind("[data-fancybox]", {
    // Custom options
  });
</script>
@endsection