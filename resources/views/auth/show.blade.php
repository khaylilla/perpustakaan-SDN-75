@extends('layouts.app')

@section('content')
<style>
  /* 🌆 Background Modern dengan Overlay */
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
    rgba(16, 53, 109, 0.62),    /* biru dongker semi transparan */
    rgba(5, 15, 29, 0.6)
  );
  backdrop-filter: brightness(0.9) contrast(1.1);
  z-index: -1;
}

  /* 🎨 Container Utama */
  .detail-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
  }

  .book-card {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    color: #fff;
    max-width: 1100px;
    width: 100%;
    overflow: hidden;
    border: 2px solid rgba(255, 165, 0, 0.4); /* aksen oranye */
    animation: fadeIn 0.7s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* 📘 Gambar Cover */
  .book-cover {
    flex: 1 1 320px;
    background: rgba(255,255,255,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 25px;
    overflow: hidden;
  }

  /* 🎞️ Slideshow (Slide ke kanan) */
  .cover-slideshow {
    position: relative;
    width: 260px;
    height: 370px;
    overflow: hidden;
    border-radius: 15px;
    border: 4px solid #ffb84d;
  }

  .cover-slideshow img {
    width: 260px;
    height: 370px;
    object-fit: cover;
    border-radius: 15px;
    position: absolute;
    top: 0;
    left: 100%;
    transition: left 1s ease-in-out;
  }

  .cover-slideshow img.active {
    left: 0;
  }

  .cover-slideshow img.prev {
    left: -100%;
  }

  /* 📋 Info Buku */
  .book-details {
    flex: 2 1 500px;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .book-details h1 {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #ffb84d;
  }

  .book-meta {
    margin-bottom: 25px;
  }

  .book-meta p {
    margin: 6px 0;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .book-meta strong {
    color: #ffcc66;
  }

  /* 📝 Deskripsi */
  .book-description {
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 12px;
    line-height: 1.6;
    font-size: 14px;
    border-left: 4px solid #ffb84d;
  }

  /* 🔙 Tombol Kembali */
  .back-button {
    margin-top: 25px;
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
  }

  /* 📍 Footer */
  footer {
    margin-top: 60px;
    background: rgba(0, 0, 0, 0.85);
    color: white;
    padding: 40px;
    text-align: center;
    border-top: 4px solid #ffb84d;
  }

  footer iframe {
    border: 0;
    width: 100%;
    height: 180px;
    border-radius: 12px;
    margin-top: 15px;
  }

  @media (max-width: 768px) {
    .book-card {
      flex-direction: column;
    }
    .book-details {
      padding: 25px;
    }
  }
</style>

<div class="overlay"></div>

<div class="detail-page">
  <div class="book-card">
    {{-- Cover Buku --}}
    <div class="book-cover">
      @php
        $covers = json_decode($book->cover, true);
      @endphp

      @if($covers && count($covers) > 0)
        <div id="cover-slideshow" class="cover-slideshow">
          @foreach($covers as $index => $cover)
            <img src="{{ asset('storage/' . $cover) }}" 
                 alt="Cover {{ $index + 1 }}" 
                 class="{{ $index === 0 ? 'active' : '' }}">
          @endforeach
        </div>
      @else
        <img src="{{ asset('images/no-image.png') }}" alt="Tidak ada cover">
      @endif
    </div>

    {{-- Detail Buku --}}
    <div class="book-details">
      <div>
        <h1>{{ $book->judul }}</h1>

        <div class="book-meta">
          <p><strong>Penulis:</strong> {{ $book->penulis ?? '-' }}</p>
          <p><strong>Penerbit:</strong> {{ $book->penerbit ?? '-' }}</p>
          <p><strong>Kategori:</strong> {{ $book->kategori ?? '-' }}</p>
          <p><strong>Tahun Terbit:</strong> {{ $book->tahun_terbit ?? '-' }}</p>
          <p><strong>Nomor Buku:</strong> {{ $book->nomor_buku ?? '-' }}</p>
          <p><strong>Rak:</strong> {{ $book->rak ?? '-' }}</p>
          <p><strong>Jumlah:</strong> {{ $book->jumlah ?? '-' }}</p>
        </div>

        <div class="book-description">
          <h3 style="color:#ffcc66;">Deskripsi</h3>
          <p>{!! nl2br(e($book->deskripsi)) !!}</p>
        </div>
      </div>

      {{-- Tombol kembali --}}
      <div class="back-button">
        <a href="{{ route('buku.index') }}">← Kembali</a>
      </div>
    </div>
  </div>

  <footer>
    <p>📍 Perpustakaan Teknik Universitas Bengkulu</p>
    <iframe
      src="https://www.google.com/maps?q=Universitas+Bengkulu&output=embed"
      allowfullscreen>
    </iframe>
  </footer>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('#cover-slideshow img');
    let current = 0;

    // Pastikan semua slide selain yang aktif ada di sebelah kanan
    slides.forEach((img, i) => {
      img.style.left = i === 0 ? '0%' : '100%';
    });

    if (slides.length > 1) {
      setInterval(() => {
        const next = (current + 1) % slides.length;

        // Geser slide aktif ke kiri
        slides[current].style.transition = 'left 1s ease-in-out';
        slides[current].style.left = '-100%';

        // Geser slide berikutnya ke posisi tengah
        slides[next].style.transition = 'left 1s ease-in-out';
        slides[next].style.left = '0%';

        // Setelah animasi selesai, reset posisi slide lama ke kanan
        setTimeout(() => {
          slides[current].style.transition = 'none';
          slides[current].style.left = '100%';
          current = next;
        }, 1000);
      }, 3000); // Ganti setiap 4 detik
    }
  });
</script>
@endsection
