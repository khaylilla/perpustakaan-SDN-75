@extends('layouts.app')

@section('content')
<style>
  /* === HERO SECTION === */
  .hero-section {
    background: linear-gradient(135deg, #0d3c84ff, #6c92deff);
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

  /* === CONTAINER === */
  .artikel-container {
    max-width: 900px;
    margin: 60px auto;
    padding: 0 20px;
  }

  /* === SEARCH BAR === */
  .search-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 40px;
  }
  .search-box {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 30px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    width: 100%;
    max-width: 550px;
    overflow: hidden;
  }
  .search-box input {
    flex: 1;
    border: none;
    outline: none;
    padding: 12px 16px;
    font-size: 15px;
  }
  .search-box button {
    background: #4a4ca4;
    border: none;
    color: white;
    padding: 12px 20px;
    cursor: pointer;
    transition: 0.3s;
  }
  .search-box button:hover { background: #3c3f91; }

  /* === LIST STYLE === */
  .artikel-list {
    display: flex;
    flex-direction: column;
    gap: 25px;
  }

  .artikel-item {
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #f0f4ff, #e6ecff);
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    padding: 20px 25px;
    transition: 0.3s ease;
  }

  .artikel-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
  }

  .artikel-item h5 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 10px;
  }

  .artikel-item p {
    font-size: 15px;
    color: #555;
    margin-bottom: 15px;
    line-height: 1.6;
  }

  .artikel-item a {
    align-self: flex-start;
    background: #f7931e;
    color: #fff;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: 0.2s;
  }
  .artikel-item a:hover {
    background: #e67e22;
  }

  @media (max-width: 768px) {
    .hero-section h1 { font-size: 2rem; }
    .hero-section p { font-size: 1rem; }
  }
</style>

{{-- === HERO SECTION === --}}
<section class="hero-section">
  <h1>Artikel Informasi</h1>
  <p>Temukan beragam artikel menarik seputar teknologi, pendidikan, dan inovasi.</p>
</section>

{{-- === MAIN CONTENT === --}}
<div class="artikel-container">
  {{-- SEARCH --}}
  <form action="{{ route('auth.artikel') }}" method="GET" class="search-wrapper">
    <div class="search-box">
      <input type="text" name="keyword" placeholder="Cari artikel berdasarkan judul atau deskripsi..." value="{{ request('keyword') }}">
      <button type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  {{-- ARTIKEL LIST --}}
  @if($artikels->count())
  <div class="artikel-list">
    @foreach($artikels as $artikel)
    <div class="artikel-item">
      <h5>{{ $artikel->judul }}</h5>
      <p>{{ Str::limit($artikel->deskripsi, 250) }}</p>
      <a href="{{ $artikel->link }}" target="_blank">Baca Selengkapnya</a>
    </div>
    @endforeach
  </div>

  @else
  <div class="text-center mt-5">
    <h5 class="text-muted">Belum ada artikel yang tersedia.</h5>
  </div>
  @endif
</div>
@endsection
