@extends('layouts.app')

@section('content')
<style>
  body {
    position: relative;
    min-height: 100vh;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    background: url('{{ asset('FT.jpg') }}') center/cover no-repeat fixed;
  }

  /* 🔸 Overlay hanya di konten, bukan navbar */
  .content-overlay {
    position: relative;
  }

  .content-overlay::before {
    content: "";
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 0;
    border-radius: 25px;
  }

  .content-overlay > * {
    position: relative;
    z-index: 1;
  }

  /* ===== About Section ===== */
  .about-container {
    max-width: 850px;
    margin: 80px auto 50px;
    background-color: #f7931e;
    color: black;
    border-radius: 25px;
    padding: 50px 40px;
    text-align: justify;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    position: relative;
  }

  .logo-wrapper {
    text-align: center;
    margin-top: -70px;
    margin-bottom: 25px;
  }

  .logo-wrapper img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background-color: #f8f9fa;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  }

  .about-text {
    text-align: justify;
    font-size: 16px;
    line-height: 1.9;
    letter-spacing: 0.3px;
  }

  /* ===== Founder Section ===== */
  .founder-section {
    background-color: #f7931e;
    border-radius: 25px;
    padding: 50px 40px;
    text-align: center;
    margin-top: 40px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  }

  .founder-section h5 {
    color: #002366;
    font-weight: bold;
    font-size: 20px;
    margin-bottom: 30px;
  }

  .founders {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 35px;
  }

  .founder {
    text-align: center;
    width: 120px;
  }

  .founder img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: #e0e0e0;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
    object-fit: cover;
  }

  .founder p {
    margin-top: 12px;
    font-size: 15px;
    color: black;
    font-weight: 500;
  }

  /* ===== Maps Section ===== */
  .map-section {
    background-color: #222;
    color: #fff;
    text-align: center;
    padding: 40px 20px;
    border-radius: 25px;
    margin-top: 40px;
  }

  .map-section iframe {
    border-radius: 10px;
    width: 100%;
    max-width: 600px;
    height: 250px;
  }

  .map-section p {
    margin-top: 10px;
    font-size: 14px;
  }

  @media (max-width: 576px) {
    .about-container, .founder-section, .map-section {
      border-radius: 0;
      padding: 30px 20px;
    }

    .about-text {
      font-size: 14px;
    }
  }
</style>

<div class="container">
  <div class="content-overlay">
    <!-- ===== Tentang Perpustakaan ===== -->
    <div class="about-container mt-5">
      <div class="logo-wrapper">
        <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
      </div>

      <div class="about-text">
        <p>
          Perpustakaan Fakultas Teknik Universitas Bengkulu melaksanakan fungsi sebagai bagian dari Tri Dharma Perguruan Tinggi, dengan tugas utama mengelola koleksi karya tulis, cetak, dan rekam dalam bidang teknologi dan rekayasa untuk mendukung pendidikan, penelitian, dan kebutuhan informasi bagi sivitas akademika Fakultas Teknik.
        </p>
        <p>
          Pengguna dari perpustakaan ini mencakup mahasiswa, staf fakultas, dan mahasiswa umum serta perguruan tinggi lainnya. Layanan yang tersedia meliputi keanggotaan, sirkulasi (peminjaman dan pengembalian) buku, peminjaman jurnal/majalah serta karya ilmiah untuk baca di tempat, layanan informasi melalui email, telepon, atau fax.
        </p>
      </div>
    </div>

    <!-- ===== Pendiri ===== -->
    <div class="founder-section mt-4">
      <h5>Pendiri Perpustakaan Fakultas Teknik</h5>
      <div class="founders">
        <div class="founder">
          <img src="{{ asset('default.jpg') }}" alt="Founder 1">
          <p>Nama<br>NIM</p>
        </div>
        <div class="founder">
          <img src="{{ asset('default.jpg') }}" alt="Founder 2">
          <p>Nama<br>NIM</p>
        </div>
        <div class="founder">
          <img src="{{ asset('default.jpg') }}" alt="Founder 3">
          <p>Nama<br>NIM</p>
        </div>
      </div>
    </div>

    <!-- ===== Lokasi Kampus ===== -->
    <div class="map-section mt-4">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127639.14433262491!2d102.2546626!3d-3.7928477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e36b0fce71e911f%3A0x3039d80b220cf40!2sUniversitas%20Bengkulu!5e0!3m2!1sid!2sid!4v1697103367079!5m2!1sid!2sid"
        allowfullscreen="" loading="lazy">
      </iframe>
      <p class="small mt-2 mb-0">Jl. WR. Supratman, Kandang Limun, Bengkulu 38371</p>
    </div>
  </div>
</div>
@endsection
