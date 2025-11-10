@extends('layouts.app')

@section('content')
<style>
  /* === HERO SECTION === */
  .hero-section {
    background: linear-gradient(135deg, #0d3c84, #6c92de);
    color: white;
    text-align: center;
    padding: 80px 20px;
    border-radius: 0 0 60px 60px;
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
  }
  .hero-section h1 {
    font-weight: 800;
    font-size: 2.5rem;
  }
  .hero-section p {
    margin-top: 10px;
    font-size: 1.1rem;
    opacity: 0.9;
  }

  /* === CONTACT SECTION === */
  .contact-section {
    max-width: 900px;
    margin: 60px auto;
    padding: 0 20px;
  }

  .contact-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: linear-gradient(135deg, #f0f4ff, #e6ecff);
    border-radius: 16px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    padding: 25px 30px;
    transition: 0.3s;
    animation: fadeInUp 0.6s ease forwards;
  }

  .contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  }

  .contact-icon {
    font-size: 2.2rem;
    color: #0d3c84;
    background: #e7a75eff;
    padding: 15px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .contact-info h5 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 5px;
  }

  .contact-info p {
    color: #555;
    font-size: 15px;
    margin: 0;
  }

  .contact-grid {
    display: flex;
    flex-direction: column;
    gap: 25px;
  }

  /* === ANIMATION === */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 768px) {
    .contact-card {
      flex-direction: column;
      align-items: flex-start;
    }
    .hero-section h1 {
      font-size: 2rem;
    }
  }
</style>

{{-- === HERO === --}}
<section class="hero-section">
  <h1>Kontak Kami</h1>
  <p>Hubungi kami untuk informasi lebih lanjut atau pertanyaan seputar layanan perpustakaan.</p>
</section>

{{-- === CONTACT CONTENT === --}}
<div class="contact-section">
  <div class="contact-grid">
    <div class="contact-card">
      <div class="contact-icon"><i class="bi bi-telephone-fill"></i></div>
      <div class="contact-info">
        <h5>Telepon</h5>
        <p>(0736) 344087</p>
      </div>
    </div>

    <div class="contact-card">
      <div class="contact-icon"><i class="bi bi-geo-alt-fill"></i></div>
      <div class="contact-info">
        <h5>Alamat</h5>
        <p>Kandang Limun, Kec. Muara Bangka Hulu, Kota Bengkulu, Bengkulu 38119</p>
      </div>
    </div>

    <div class="contact-card">
      <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
      <div class="contact-info">
        <h5>Email</h5>
        <p>perpusftunib@gmail.com</p>
      </div>
    </div>
  </div>
</div>
@endsection
