@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    use Carbon\Carbon;
@endphp

@extends('admin.layout')

@section('page-title', 'Generate Kartu Anggota')

@section('content')

<style>
/* ======= Info Boxes ======= */
.info-boxes { display: flex; flex-wrap: wrap; gap: 25px; margin-bottom: 25px; }
.info-box { background: linear-gradient(135deg, #f7931e, #ffb347); color: white; border-radius: 20px; width: 250px; padding: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: space-between; text-decoration: none; transition: transform 0.25s ease; }
.info-box:hover { transform: translateY(-5px); box-shadow: 0 10px 22px rgba(0,0,0,0.2); }
.info-box i { font-size: 36px; opacity: 0.85; }
.info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
.info-box-content p { font-size: 13px; margin-top: 5px; }

/* ====== Tabel dengan border ====== */
table { width: 100%; border-collapse: collapse; font-size: 14px; }
table th, table td { border: 1px solid #ccc; padding: 8px; text-align: center; }

/* ====== Preview Mini Kartu ====== */
.mini-card { width: 120px; height: 80px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; font-size: 10px; padding: 2px; position: relative; cursor:pointer; }
.mini-card::before { content:""; position:absolute; inset:0; background:url('{{ asset('FT.jpg') }}') center/cover no-repeat; opacity:0.3; z-index:0; }
.mini-card * { position: relative; z-index:1; }
.mini-card .header { background-color: #f8b600; color: #000; text-align: center; font-weight: bold; font-size: 8px; padding: 2px 0; border-radius: 3px; }
.mini-card .body { display:flex; gap:2px; padding:2px; align-items:center; }
.mini-card .body img { width:25px; height:25px; object-fit:cover; border-radius:3px; }
.mini-card .body .info { flex:1; font-size:7px; line-height:1; }
.mini-card .footer { text-align:center; font-size:6px; font-weight:bold; padding-bottom:2px; }

/* Tombol generate mini */
.btn-generate-mini { font-size: 12px; padding: 4px 8px; border-radius: 5px; border: none; cursor: pointer; margin-top:4px; }
.btn-generate-mini.active { background-color:#f7931e; color:white; }
.btn-generate-mini.disabled { background-color:#eee; color:#aaa; cursor:not-allowed; }

 .search-bar {
  background: white;
  border-radius: 12px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  padding: 10px 15px;
  margin-bottom: 20px;
  width: 100%;
  max-width: 80%;  /* agar penuh selebar container */
}

 .search-bar input {
  border: none;
  outline: none;
  flex: 1;
  font-size: 15px;
  padding-left: 8px;
}

.search-bar .btn {
  white-space: nowrap;
}
</style>

<div class="container-fluid">
  <div class="info-boxes">
    <a href="{{ route('admin.absen.scan') }}" class="info-box">
      <div class="info-box-content">
        <h5>Scan Absen</h5>
        <p>Scan kartu anggota</p>
      </div>
      <i class="bi bi-qr-code-scan"></i>
    </a>
    <a href="{{ route('admin.dataabsen') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Absen</h5>
        <p>Lihat riwayat absensi</p>
      </div>
      <i class="bi bi-table"></i>
    </a>
    <a href="{{ route('admin.kartu.generate') }}" class="info-box">
      <div class="info-box-content">
        <h5>Generate Kartu</h5>
        <p>Buat kartu anggota</p>
      </div>
      <i class="bi bi-credit-card-2-back-fill"></i>
    </a>
  </div>
  {{-- SEARCH BAR --}}
    <form action="{{ route('admin.kartu.generate') }}" method="GET" class="search-bar w-100 mb-3">
        <i class="bi bi-search"></i>
        <input type="text" name="keyword" id="searchInput" placeholder="Cari Nama Anggota..." value="{{ request('keyword') }}">
        <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </form>

{{-- SORT & FILTER BAR --}}
  <div class="sort-filter-bar">
    <label for="filter-date" class="fw-semibold">Pilih Tanggal:</label>
    <input type="date" id="filter-date" class="form-control"><br>

</div>
  </div>

  <table>
      <thead>
          <tr>
              <th>Preview Kartu</th>
              <th>Foto</th>
              <th>Nama</th>
              <th>NPM</th>
              <th>Masa Aktif</th>
              <th>Status</th>
          </tr>
      </thead>
     <tbody>
    @forelse($users as $user)
          @php
            $masa_aktif_text = 'Berlaku Seumur Hidup';
          @endphp
        <tr>
            <td>
                <div class="mini-card" 
                     data-nama="{{ $user->nama }}" 
                     data-npm="{{ $user->npm }}" 
                 data-masa="{{ $masa_aktif_text }}"
                     data-foto="{{ asset('storage/foto/'.$user->foto) }}">
                    <div class="header">PERPUSTAKAAN</div>
                    <div class="body">
                        <img src="{{ asset('storage/foto/'.$user->foto) }}" alt="Foto">
                        <div class="info">
                            <div>{{ $user->nama }}</div>
                            <div>{{ $user->npm }}</div>
                        </div>
                    </div>
                <div class="footer">{{ $masa_aktif_text }}</div>
              </div>
            </td>
            <td><img src="{{ asset('storage/foto/'.$user->foto) }}" style="width:50px; height:50px; object-fit:cover;"></td>
            <td>{{ $user->nama }}</td>
            <td>{{ $user->npm }}</td>
            <td>{{ $masa_aktif_text }}</td>
            <td><span style="color:green;font-weight:bold;">Aktif</span></td>
        </tr>
    @empty
        <tr>
            <td colspan="6">Belum ada data kartu anggota</td>
        </tr>
    @endforelse
</tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

<script>
$(document).ready(function(){
    // Kartu bersifat permanen sekarang, tidak perlu tombol generate lagi.
});
</script>
@endsection
