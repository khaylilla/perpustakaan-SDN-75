@extends('layouts.app')

@section('content')
<style>
  body {
    background: url('{{ asset('FT.jpg') }}') center/cover no-repeat fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    position: relative;
  }

  .overlay {
    position: fixed;
    inset: 0;
    background: linear-gradient(
      rgba(16, 53, 109, 0.62),
      rgba(5, 15, 29, 0.6)
    );
    backdrop-filter: brightness(0.9) contrast(1.1);
    z-index: -1;
  }

  .detail-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
  }

  .artikel-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    color: #fff;
    max-width: 900px;
    width: 100%;
    overflow: hidden;
    border: 2px solid rgba(255, 165, 0, 0.4);
    animation: fadeIn 0.7s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .artikel-header {
    padding: 40px;
    background: rgba(255, 165, 0, 0.1);
  }

  .artikel-header h1 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 15px 0;
    color: #ffb84d;
  }

  .artikel-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 15px;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
  }

  .meta-item strong {
    color: #ffcc66;
  }

  .badge-kategori {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
  }

  .badge-informasi { background-color: #3498db; }
  .badge-berita { background-color: #e74c3c; }
  .badge-artikel { background-color: #27ae60; }

  .artikel-content {
    padding: 40px;
  }

  .artikel-foto {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 30px;
  }

  .artikel-subjudul {
    font-size: 18px;
    color: #ffcc66;
    margin-bottom: 20px;
    font-weight: 600;
  }

  .artikel-text {
    line-height: 1.8;
    font-size: 15px;
    margin-bottom: 25px;
    text-align: justify;
  }

  .artikel-link {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #ffb84d, #ffa94d);
    color: #002b6b;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s ease;
  }

  .artikel-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 184, 77, 0.4);
    color: #002b6b;
  }

  .back-button {
    margin-top: 30px;
    display: flex;
    justify-content: flex-start;
  }

  .back-button a {
    text-decoration: none;
    background: linear-gradient(135deg, #001f4d, #004aad);
    color: #fff;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 10px;
    transition: 0.3s ease;
    border: 2px solid #ffb84d;
  }

  .back-button a:hover {
    background: linear-gradient(135deg, #002b6b, #0058c2);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 184, 77, 0.4);
    color: #fff;
  }

  @media (max-width: 768px) {
    .detail-page {
      padding: 40px 15px;
    }
    .artikel-header, .artikel-content {
      padding: 25px;
    }
    .artikel-header h1 {
      font-size: 24px;
    }
    .artikel-meta {
      flex-direction: column;
      gap: 10px;
    }
  }
</style>

<div class="overlay"></div>

<div class="detail-page">
  <div class="artikel-card">
    {{-- Header --}}
    <div class="artikel-header">
      <div class="meta-item" style="margin-bottom: 15px;">
        @php
          $kategoriBadge = [
            'Informasi/Pengumuman' => 'badge-informasi',
            'Berita' => 'badge-berita',
            'Artikel' => 'badge-artikel'
          ];
        @endphp
        <span class="badge-kategori {{ $kategoriBadge[$artikel->kategori] ?? 'badge-informasi' }}">
          {{ $artikel->kategori }}
        </span>
      </div>
      <h1>{{ $artikel->judul }}</h1>
      <div class="artikel-meta">
        <div class="meta-item">
          <i class="bi bi-calendar3"></i>
          <span>{{ $artikel->created_at->format('d M Y') }}</span>
        </div>
        <div class="meta-item">
          <i class="bi bi-clock"></i>
          <span>{{ $artikel->created_at->format('H:i') }}</span>
        </div>
      </div>
    </div>

    {{-- Content --}}
    <div class="artikel-content">
      @if($artikel->foto)
        <img src="{{ asset('storage/' . $artikel->foto) }}" alt="Foto Artikel" class="artikel-foto">
      @endif

      @if($artikel->subjudul)
        <div class="artikel-subjudul">{{ $artikel->subjudul }}</div>
      @endif

      <div class="artikel-text">
        {!! nl2br(e($artikel->isi)) !!}
      </div>

      @if($artikel->link)
        <a href="{{ $artikel->link }}" target="_blank" class="artikel-link">
          <i class="bi bi-link-45deg"></i> Baca Selengkapnya
        </a>
      @endif

      {{-- Back Button --}}
      <div class="back-button">
        <a href="javascript:history.back()">← Kembali</a>
      </div>
    </div>
  </div>
</div>

@include('components.footer')
@endsection
