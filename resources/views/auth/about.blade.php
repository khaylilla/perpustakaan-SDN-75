@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

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

  /* ANIMASI BACKGROUND BUBBLES (Sama dengan Artikel) */
  .bg-animated {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    z-index: -1; background: #fff; overflow: hidden;
  }
  .bubble {
    position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.12; animation: float 20s infinite alternate;
  }
  .bubble-1 { width: 400px; height: 400px; background: var(--primary-blue); top: -100px; right: -100px; }
  .bubble-2 { width: 300px; height: 300px; background: var(--accent-red); bottom: -50px; left: -50px; animation-delay: -5s; }
  .bubble-3 { width: 250px; height: 250px; background: var(--deep-navy); top: 40%; left: 10%; animation-delay: -10s; }

  @keyframes float {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(50px, 100px) scale(1.2); }
  }

  /* ==== HERO SECTION ==== */
  .hero-wrapper {
    padding: 40px 0 30px; /* Dinaikkan mendekat navbar */
    text-align: center;
  }

  .main-title {
    font-family: var(--font-heading);
    font-size: clamp(2.2rem, 6vw, 3.8rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1px;
    margin-bottom: 15px;
    background: linear-gradient(135deg, var(--deep-navy) 30%, var(--primary-blue) 60%, var(--accent-red) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: drop-shadow(0 10px 15px rgba(2, 31, 75, 0.1));
  }

  /* ==== INFO CARDS (POP UP STYLE) ==== */
  .grid-info {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 60px;
  }

  .glass-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid #e2e8f0;
    border-radius: 28px;
    padding: 30px;
    transition: all 0.5s cubic-bezier(0.2, 1, 0.22, 1);
    box-shadow: 0 10px 20px rgba(0,0,0,0.02);
  }

  .glass-card:hover {
    transform: translateY(-12px) scale(1.02);
    background: var(--pure-white);
    box-shadow: 0 30px 60px rgba(2, 31, 75, 0.08);
    border-color: var(--primary-blue);
  }

  .glass-card i {
    font-size: 2rem;
    color: var(--accent-red);
    margin-bottom: 15px;
    display: block;
  }

  /* ==== GALLERY SECTION ==== */
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
    margin-bottom: 30px;
    color: var(--deep-navy);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.9rem;
  }

  .section-label::after {
    content: "";
    height: 2px;
    flex-grow: 1;
    background: linear-gradient(to right, var(--accent-red), transparent);
    opacity: 0.3;
  }

  .gallery-item {
    width: 400px; 
    height: 270px;
    border-radius: 30px;
    overflow: hidden;
    position: relative;
    border: 2px solid #f1f5f9;
    cursor: zoom-in;
    transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .gallery-item img {
    width: 100%; height: 100%; object-fit: cover; transition: 0.8s;
  }

  .gallery-item:hover {
    border-color: var(--primary-blue);
    transform: scale(1.02);
    box-shadow: 0 20px 40px rgba(2, 31, 75, 0.15);
  }

  .gallery-item:hover img { transform: scale(1.1); }

  /* ==== NAV BUTTONS (MENYALA) ==== */
  .nav-btn {
    position: absolute;
    top: 55%;
    transform: translateY(-50%);
    width: 50px;
    height: 50px;
    background: var(--deep-navy);
    color: white;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: 0.3s;
    border: none;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .nav-btn:hover { 
    background: var(--accent-red); 
    box-shadow: 0 0 20px var(--accent-red);
    transform: translateY(-50%) scale(1.1); 
  }
  .prev-btn { left: -25px; }
  .next-btn { right: -25px; }

  @media(max-width: 1200px) {
    .prev-btn { left: 10px; }
    .next-btn { right: 10px; }
  }

  @media(max-width: 768px) {
    .grid-info { grid-template-columns: 1fr; }
    .gallery-item { width: 85vw; height: 230px; }
    .nav-btn { display: none; }
  }
</style>

<div class="bg-animated">
  <div class="bubble bubble-1"></div>
  <div class="bubble bubble-2"></div>
  <div class="bubble bubble-3"></div>
</div>

<div class="container">
  <div class="hero-wrapper" data-aos="fade-up">
    <h1 class="main-title">Modernitas dalam <br>Genggaman Literasi.</h1>
    <p class="text-muted" style="font-size: 1.1rem; max-width: 650px; margin: 0 auto; opacity: 0.9;">
      Pusat rujukan teknik digital yang menghubungkan tradisi akademik dengan teknologi masa depan.
    </p>
  </div>

  <div class="grid-info">
    <div class="glass-card" data-aos="fade-up" data-aos-delay="100">
      <i class="bi bi-eye-fill"></i>
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--deep-navy); font-size: 0.85rem; letter-spacing: 1px;">Visi Utama</h6>
      <p class="text-muted small m-0">Menjadi rujukan teknik terbaik dengan akses digital inklusif bagi seluruh sivitas akademika.</p>
    </div>
    <div class="glass-card" data-aos="fade-up" data-aos-delay="200">
      <i class="bi bi- lightning-charge-fill"></i>
      <i class="bi bi-stars"></i>
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--deep-navy); font-size: 0.85rem; letter-spacing: 1px;">Layanan Prima</h6>
      <p class="text-muted small m-0">Sirkulasi koleksi fisik dan akses database jurnal internasional secara real-time dan terintegrasi.</p>
    </div>
    <div class="glass-card" data-aos="fade-up" data-aos-delay="300">
      <i class="bi bi-building-check"></i>
      <h6 class="fw-bold text-uppercase mb-2" style="color: var(--deep-navy); font-size: 0.85rem; letter-spacing: 1px;">Fasilitas</h6>
      <p class="text-muted small m-0">Ruang kolaborasi yang dirancang untuk merangsang riset, kreativitas, dan inovasi mahasiswa.</p>
    </div>
  </div>

  <div class="gallery-container" data-aos="fade-up">
    <div class="section-label">
      <i class="bi bi-camera-fill"></i> Galeri Perpustakaan
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
    centeredSlides: false,
    speed: 800,
    navigation: {
      nextEl: ".next-btn",
      prevEl: ".prev-btn",
    },
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
  });

  Fancybox.bind("[data-fancybox]", {
    // Custom options
  });
</script>
@endsection