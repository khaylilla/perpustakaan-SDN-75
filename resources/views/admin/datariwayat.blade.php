@extends('admin.layout')

@section('page-title', 'Manajemen Data Riwayat')

@section('content')
<style>
  /* === KOTAK INFO === */
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
    margin-top: 30px;
    margin-bottom: 40px;
  }

  .info-box {
    background: linear-gradient(135deg, #f7931e, #ffb347);
    color: #fff;
    border-radius: 20px;
    width: 360px; /* 💡 lebih besar */
    height: 160px; /* 💡 lebih tinggi */
    padding: 24px 30px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: all 0.3s ease;
  }

  .info-box:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  }

  .info-box i {
    font-size: 50px;
    opacity: 0.85;
  }

  .info-box-content h5 {
    margin: 0;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: 0.5px;
  }

  .info-box-content p {
    font-size: 14px;
    margin-top: 5px;
    opacity: 0.9;
  }

  /* 🌐 Responsif */
  @media (max-width: 768px) {
    .info-box {
      width: 100%;
      height: 130px;
      padding: 20px;
    }

    .info-box-content h5 {
      font-size: 18px;
    }

    .info-box i {
      font-size: 40px;
    }
  }
</style>

<div class="container-fluid">
  <div class="info-boxes">

    {{-- 📘 Peminjaman --}}
    <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Peminjaman</h5>
        <p>Riwayat peminjaman buku oleh pengguna</p>
      </div>
      <i class="bi bi-book-half"></i>
    </a>

    {{-- 📗 Pengembalian --}}
    <a href="{{ route('admin.riwayat.pengembalian.pengembalian') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Pengembalian</h5>
        <p>Riwayat pengembalian buku yang sudah dipinjam</p>
      </div>
      <i class="bi bi-arrow-repeat"></i>
    </a>

  </div>
</div>
@endsection
