@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="text-center">

  <!-- ===== Header / Hero Section ===== -->
  <div class="position-relative text-white">
    <img src="{{ asset('FT.jpg') }}" 
         class="img-fluid w-100" 
         alt="FT UNIB" 
         style="max-height: 320px; object-fit: cover; opacity: 0.85;">

    <!-- Kotak teks dengan background putih semi-transparan -->
    <div class="position-absolute top-50 start-50 translate-middle text-start p-4 rounded shadow" 
         style="max-width: 700px; background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(3px);">
      <h6 class="fw-bold text-primary">Selamat Datang</h6>
      <h5 class="fw-bold text-dark mb-2">
        Perpustakaan Fakultas Teknik<br>Universitas Bengkulu
      </h5>
      <p class="text-dark small mb-0">
        Temukan ilmu, jelajahi pengetahuan, dan wujudkan ide inovatifmu di perpustakaan kami.  
        Setiap buku adalah jendela menuju dunia baru.
      </p>
    </div>

    <!-- Logo UNIB agak jauh di kanan atas -->
    <img src="{{ asset('unib.jpg') }}" 
         alt="Logo UNIB" 
         class="position-absolute end-0 top-0 me-5 mt-4 rounded-circle shadow" 
         width="100">
  </div>

  <!-- ===== Bagian Koleksi Buku ===== -->
  <div class="py-4" style="background-color: #ff9800; color: white;">
    <p class="fst-italic mb-4">
      Inilah ruang tempat kalian menimba ilmu, menjelajahi pengetahuan, dan menemukan jawaban bagi rasa ingin tahu yang tak terbatas.
    </p>

    <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
      @for ($i = 1; $i <= 4; $i++)
      <div class="bg-white rounded p-2" style="width: 90px; height: 130px;">
        <small class="text-dark d-block mt-5">Judul<br><span class="text-muted small">Jenis</span></small>
      </div>
      @endfor
    </div>

    <button class="btn btn-light text-dark px-4">Koleksi</button>
  </div>

  <!-- ===== Buku Terpopuler & Artikel ===== -->
  <div class="container my-5">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #1976d2; color: white;">
          <h5 class="text-start mb-3">Buku Terpopuler</h5>
          <div class="d-flex justify-content-around">
            @for ($i = 1; $i <= 2; $i++)
            <div class="bg-white rounded p-2 text-center" style="width: 100px; height: 130px;">
              <small class="text-dark d-block mt-5">Judul<br><span class="text-muted small">Jenis</span></small>
            </div>
            @endfor
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="p-3 rounded" style="background-color: #ff9800; color: white;">
          <h5 class="text-start mb-3">Artikel</h5>
          <div class="text-start">
            <p><strong>Judul</strong><br><a href="#" class="text-light small text-decoration-none">Link artikel</a></p>
            <p><strong>Judul</strong><br><a href="#" class="text-light small text-decoration-none">Link artikel</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== Lokasi Kampus ===== -->
  <div class="bg-dark text-white py-4">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127639.14433262491!2d102.2546626!3d-3.7928477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e36b0fce71e911f%3A0x3039d80b220cf40!2sUniversitas%20Bengkulu!5e0!3m2!1sid!2sid!4v1697103367079!5m2!1sid!2sid"
      width="300" height="150" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
    <p class="small mt-2 mb-0">Jl. WR. Supratman, Kandang Limun, Bengkulu 38371</p>
  </div>

</div>
@endsection
