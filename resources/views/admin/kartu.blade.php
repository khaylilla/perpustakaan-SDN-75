@extends('admin.layout')

@section('page-title', 'Cetak Kartu Anggota A4')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap');

  :root {
    /* Ukuran standar kartu (ID Card) agar muat 6 di A4 */
    --card-width: 9cm;
    --card-height: 5.5cm;
    --dark-maroon: #631e1e; 
    --text-navy: #1a2a4e;
  }

  body {
    background-color: #f0f2f5;
    font-family: 'Inter', sans-serif;
  }

  /* Grid untuk tampilan layar: 2 kolom */
  .card-grid {
    display: grid;
    grid-template-columns: repeat(2, var(--card-width));
    gap: 15px;
    justify-content: center;
    padding: 20px;
  }

  .member-card {
    position: relative;
    width: var(--card-width);
    height: var(--card-height);
    background-color: #ffffff;
    background-image: url('{{ asset("FT.jpg") }}'); 
    background-size: cover;
    background-position: center;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    page-break-inside: avoid; /* Penting untuk cetak */
  }

  .card-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.7); 
    z-index: 1;
  }

  .card-content {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 12px 15px;
  }

  /* Header */
  .header-section {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
  }

  .unib-logo {
    width: 45px;
    height: 45px;
    background-color: rgba(255,255,255,0.9);
    border-radius: 8px;
    padding: 3px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .unib-logo img {
    width: 90%;
    height: auto;
  }

  .school-title h1 {
    margin: 0;
    font-size: 14px;
    font-weight: 900;
    color: var(--dark-maroon);
    text-transform: uppercase;
  }

  .school-title p {
    margin: 0;
    font-size: 9px;
    font-weight: 700;
    color: var(--text-navy);
  }

  /* Body */
  .body-section {
    display: grid;
    grid-template-columns: 70px 1fr 80px;
    gap: 8px;
    align-items: center;
    flex-grow: 1;
  }

  .photo-frame {
    width: 70px;
    height: 90px;
    background: #eee;
    border-radius: 8px;
    border: 1.5px solid white;
    overflow: hidden;
  }

  .info-data {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .label-text {
    font-size: 7px;
    font-weight: 800;
    color: #666;
    text-transform: uppercase;
  }

  .value-text {
    font-size: 11px;
    font-weight: 900;
    color: var(--text-navy);
    text-transform: uppercase;
    line-height: 1.1;
    word-wrap: break-word;
    overflow: hidden;
  }

  .barcode-wrapper {
    background: #ffffff;
    border-radius: 8px;
    padding: 5px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .card-footer-strip {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 10px;
    background-color: var(--dark-maroon);
    z-index: 3;
  }

  /* Pengaturan Cetak A4 (6 kartu) */
  @media print {
    @page {
      size: A4;
      margin: 1cm;
    }
    body { background: none; padding: 0; }
    .header-controls { display: none !important; }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr); /* 2 kolom */
      grid-template-rows: repeat(3, 1fr);    /* 3 baris */
      gap: 10px;
      padding: 0;
      margin: 0;
    }
    .member-card {
      box-shadow: none;
      border: 0.5px solid #ddd;
      -webkit-print-color-adjust: exact;
    }
  }
</style>

<div class="container-fluid py-4">
  <div class="header-controls d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
    <div>
      <h5 class="fw-bold mb-0">📇 Format Cetak A4 (6 Kartu)</h5>
      <small class="text-muted">Gunakan kertas A4 untuk hasil terbaik</small>
    </div>
    <button onclick="window.print()" class="btn btn-dark px-4">
      <i class="bi bi-printer me-2"></i> Cetak Sekarang
    </button>
  </div>

  <div class="card-grid">
    @foreach($anggota as $i => $item)
      <div class="member-card">
        <div class="card-overlay"></div>
        
        <div class="card-content">
          <div class="header-section">
            <div class="unib-logo">
              <img src="{{ asset('unib.jpg') }}" alt="Logo">
            </div>
            <div class="school-title">
              <h1>PERPUSTAKAAN SDN 75</h1>
              <p>SEKOLAH DASAR NEGERI 75</p>
            </div>
          </div>

          <div class="body-section">
            <div class="photo-frame">
               <img src="{{ asset('foto profil.png') }}" style="width:100%; height:100%; object-fit:cover;">
            </div>

            <div class="info-data">
              <div class="data-group">
                <div class="label-text">NAMA LENGKAP</div>
                <div class="value-text">{{ $item->nama }}</div>
              </div>

              <div class="data-group">
                <div class="label-text">{{ $item->type === 'siswa' ? 'NISN' : ($item->type === 'guru' ? 'NIP' : 'EMAIL') }}</div>
                <div class="value-text">{{ $item->identifier }}</div>
              </div>
            </div>

            <div class="barcode-wrapper">
              <svg id="barcode-{{ $i }}"></svg>
            </div>
          </div>
        </div>

        <div class="card-footer-strip"></div>
      </div>
    @endforeach
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
  window.onload = function() {
    @foreach($anggota as $i => $item)
      JsBarcode("#barcode-{{ $i }}", "{{ $item->identifier }}", {
        format: "CODE128",
        width: 1.2,
        height: 35,
        displayValue: false,
        margin: 0
      });
    @endforeach
  };
</script>
@endsection