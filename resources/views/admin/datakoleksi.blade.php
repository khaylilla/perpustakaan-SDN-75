@extends('admin.layout')

@section('page-title', 'Manajemen Data Koleksi')

@section('content')
@php
  // pastikan variabel $books tidak ditampilkan otomatis
  if (isset($books) && $books instanceof \Illuminate\Pagination\LengthAwarePaginator) {
      $books->withPath(request()->url());
  }
@endphp

<style>
  /* === KOTAK INFO === */
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
    align-items: center;
  }
  .info-box {
    background: linear-gradient(135deg, #f7931e, #ffa94d);
    color: white;
    border-radius: 16px;
    width: 280px;
    padding: 16px 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: transform 0.2s ease;
  }
  .info-box:hover { transform: translateY(-3px); }
  .info-box i { font-size: 32px; opacity: 0.7; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin: 3px 0 0 0; }

  .btn-add {
    background-color: #4a4ca4;
    color: #fff;
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 14px;
  }
  .btn-add:hover { background-color: #3c3f91; color: #fff; }

  /* === FILTER BAR === */
  .filter-bar {
    background: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    display: inline-block;
  }
  .filter-bar form { flex-wrap: nowrap !important; }
  .filter-select { width: 160px; }
  @media (max-width: 768px) {
    .filter-bar form { flex-wrap: wrap !important; }
    .filter-select { width: 100%; }
  }

  .search-bar {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    padding: 8px 12px;
    margin-bottom: 15px;
    width: 100%;
    max-width: 750px;
  }
  .search-bar input { border: none; outline: none; flex: 1; font-size: 14px; padding-left: 8px; }
  .search-bar .btn { white-space: nowrap; }

   /* === TABEL === */
  .table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
    margin-top: 10px;
    max-height: 650px; /* batasi tinggi tabel agar scroll jika > 10 baris */
  }

  table { margin-bottom: 0; min-width: 1000px; width: 100%; border-collapse: collapse; }
  table th, table td {
    vertical-align: top !important;
    white-space: normal;
    text-align: left;
    font-size: 13px;
    padding: 8px 6px;
    border: 1px solid #ddd;
  }
  table th { background-color: #f2f2f2; }

  table th.cover-col, table td.cover-col { width: 70px; text-align: center; }
  table th.qr-col, table td.qr-col { width: 110px; text-align: center; }

  .table tbody tr:hover { background-color: #f6f6ff; }
  .action-icons i { font-size: 18px; cursor: pointer; margin: 0 6px; }
  .action-icons .edit { color: #f39c12; }
  .action-icons .delete { color: #e74c3c; }
  .cover-preview { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 4px; }
  .qr-preview { width: 100px !important; height: 100px !important; margin: auto; }

  /* 🔴 Tambahan highlight merah */
  input.is-invalid, select.is-invalid, textarea.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25);
  }
</style>

<div class="container-fluid">

  {{-- HEADER BAR --}}
  <div class="d-flex flex-wrap gap-3 mb-4 align-items-center">
    <a href="{{ route('admin.datakoleksi') }}" class="info-box">
      <div class="info-box-content">
        <h5>Manajemen Data Koleksi</h5>
        <p>Buku & Artikel</p>
      </div>
      <i class="bi bi-journal-bookmark-fill"></i>
    </a>
    <a href="{{ route('admin.dataartikel') }}" class="info-box">
      <div class="info-box-content">
        <h5>Manajemen Data Artikel</h5>
        <p>Artikel terbaru</p>
      </div>
      <i class="bi bi-file-text-fill"></i>
    </a>
    <div class="ms-auto d-flex gap-2 align-items-center">
      <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Tambah Koleksi</button>
      <a href="{{ route('admin.datakoleksi.pdf', request()->all()) }}" target="_blank" class="btn btn-success">
        <i class="bi bi-file-earmark-pdf-fill"></i> Cetak PDF
      </a>
    </div>
  </div>

  {{-- SEARCH BAR --}}
  <form action="{{ route('admin.datakoleksi') }}" method="GET" class="search-bar mb-3">
    <i class="bi bi-search"></i>
    <input type="text" name="keyword" placeholder="Cari judul, penulis, atau kategori..." value="{{ request('keyword') }}">
    <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
  </form>

 {{-- FILTER --}}
<div class="filter-bar mb-3">
  <form action="{{ route('admin.datakoleksi') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
    <select name="filter_kategori" class="form-select form-select-sm filter-select">
      <option value="">-- Semua Kategori --</option>
      <option value="Bacaan" {{ request('filter_kategori') == 'Bacaan' ? 'selected' : '' }}>Bacaan</option>
      <option value="Skripsi" {{ request('filter_kategori') == 'Skripsi' ? 'selected' : '' }}>Skripsi</option>
      <option value="Referensi" {{ request('filter_kategori') == 'Referensi' ? 'selected' : '' }}>Referensi</option>
    </select>

   <input type="date" name="filter_tanggal" class="form-select form-select-sm filter-select"
       value="{{ request('filter_tanggal') }}">

       <select name="filter_status" class="form-select form-select-sm filter-select">
      <option value="">-- Semua Status --</option>
      <option value="Tersedia" {{ request('filter_status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
      <option value="Dipinjam" {{ request('filter_status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
  </form>
</div>

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- TABEL DATA --}}
<div class="table-container">
  <table class="table text-center align-middle mb-0">
    <thead>
      <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Penerbit</th>
        <th>Tahun Terbit</th>
        <th>Kategori</th>
        <th>Nomor Buku</th>
        <th>Rak</th>
        <th>Barcode</th>
        <th>Status</th>
        <th>Jumlah</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($books as $index => $book)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>
            @if($book->cover)
              @foreach(json_decode($book->cover, true) as $path)
                <img src="{{ asset('storage/' . $path) }}" class="cover-preview" alt="cover">
              @endforeach
            @else
              -
            @endif
          </td>
          <td>{{ $book->judul }}</td>
          <td>{{ $book->penulis }}</td>
          <td>{{ $book->penerbit }}</td>
          <td>{{ $book->tahun_terbit }}</td>
          <td>{{ $book->kategori }}</td>
          <td>{{ $book->nomor_buku }}</td>
          <td>{{ $book->rak }}</td>
          <td>
            @if($book->nomor_buku)
             <div id="qrTable{{ $book->id }}" 
                class="qr-preview"
                data-qr-value="{{ $book->nomor_buku }}">
            </div>
              <button class="btn btn-sm btn-outline-secondary mt-1" onclick="downloadQR('qrTable{{ $book->id }}', '{{ $book->judul }}')">Save PNG</button>
            @else
              <span class="text-muted">Belum Ada</span>
            @endif
          </td>
          <td>{{ $book->status ?? '-' }}</td>
          <td>{{ $book->jumlah ?? 0 }}</td>
          <td>{{ Str::limit($book->deskripsi, 50) }}</td>
          <td class="action-icons">
            <i class="bi bi-pencil-square edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $book->id }}"></i>
            <form action="{{ route('admin.datakoleksi.delete', $book->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="return confirm('Yakin ingin menghapus koleksi ini?')">
                <i class="bi bi-trash-fill delete"></i>
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="14" class="text-muted">Belum ada data koleksi.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Koleksi Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form class="needs-validation" novalidate action="{{ route('admin.datakoleksi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" class="form-control" required>
              <div class="invalid-feedback">Judul wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Penulis</label>
              <input type="text" name="penulis" class="form-control" required>
              <div class="invalid-feedback">Penulis wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Penerbit</label>
              <input type="text" name="penerbit" class="form-control" required>
              <div class="invalid-feedback">Penerbit wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Tahun Terbit</label>
              <input type="text" name="tahun_terbit" class="form-control only-number" maxlength="4" required>
              <div class="invalid-feedback">Masukkan tahun terbit dengan angka saja.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control" required>
              <div class="invalid-feedback">Kategori wajib diisi.</div>
            </div>

            <div class="mb-3">
                <label for="nomor_buku_add" class="form-label">Nomor Buku</label>
                <div class="input-group">
                    <input type="text" id="nomor_buku_add" name="nomor_buku" class="form-control" readonly>
                    <button type="button" class="btn btn-primary" id="generateNomorBukuAdd">Generate</button>
                </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Barcode</label>
              <div class="d-flex gap-2 align-items-center">
                <div id="qrAddPreview"></div>
                <button type="button" class="btn btn-sm btn-outline-success" id="btnGenerateQRAdd">Generate QR</button>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Rak</label>
              <input type="text" name="rak" class="form-control" required>
              <div class="invalid-feedback">Rak wajib diisi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="">-- Pilih Status --</option>
                <option value="Tersedia">Tersedia</option>
                <option value="Dipinjam">Dipinjam</option>
              </select>
              <div class="invalid-feedback">Pilih status koleksi.</div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jumlah</label>
              <input type="number" name="jumlah" class="form-control" min="1" required>
              <div class="invalid-feedback">Jumlah wajib diisi.</div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" required></textarea>
              <div class="invalid-feedback">Deskripsi wajib diisi.</div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Upload Cover</label>
              <input type="file" name="cover[]" class="form-control" multiple required>
              <div class="invalid-feedback">Cover wajib diunggah.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- MODAL EDIT --}}
@foreach($books as $book)
<div class="modal fade" id="editModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Edit Koleksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form class="needs-validation" novalidate action="{{ route('admin.datakoleksi.update', $book->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" class="form-control" value="{{ $book->judul }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Penulis</label>
              <input type="text" name="penulis" class="form-control" value="{{ $book->penulis }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Penerbit</label>
              <input type="text" name="penerbit" class="form-control" value="{{ $book->penerbit }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tahun Terbit</label>
              <input type="text" name="tahun_terbit" class="form-control only-number" value="{{ $book->tahun_terbit }}" maxlength="4" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control" value="{{ $book->kategori }}" required>
            </div>

            <div class="mb-3">
                <label for="nomor_buku_edit{{ $book->id }}" class="form-label">Nomor Buku</label>
                <div class="input-group">
                    <input type="text" id="nomor_buku_edit{{ $book->id }}" name="nomor_buku" class="form-control" value="{{ $book->nomor_buku }}" readonly>
                    <button type="button" class="btn btn-primary btn-generate-nomor-edit" data-id="{{ $book->id }}">
                        Generate
                    </button>
                </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Barcode</label>
              <div class="d-flex gap-2 align-items-center">
                <div id="qrEditPreview{{ $book->id }}"></div>
                <button type="button" class="btn btn-sm btn-outline-success btn-generate-qr-edit" data-id="{{ $book->id }}">Generate QR</button>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Rak</label>
              <input type="text" name="rak" class="form-control" value="{{ $book->rak }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="Tersedia" {{ $book->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipinjam" {{ $book->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jumlah</label>
              <input type="number" name="jumlah" class="form-control" min="1" value="{{ $book->jumlah }}" required>
            </div>

            <div class="col-md-12">
              <label class="form-label">Deskripsi</label>
              <textarea name="deskripsi" class="form-control" required>{{ $book->deskripsi }}</textarea>
            </div>

            <div class="col-md-12">
              <label class="form-label">Upload Cover Baru (Opsional)</label>
              <input type="file" name="cover[]" class="form-control" multiple>
              <div class="small text-muted mt-1">Kosongkan jika tidak ingin mengganti cover.</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning text-white">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

     // === GENERATE QR OTOMATIS UNTUK TABEL ===
    document.querySelectorAll('[id^="qrTable"]').forEach(function(div) {
        const value = div.dataset.qrValue;
        if(value){
            new QRCode(div, { text: value, width: 100, height: 100 });
        }
    });

    // === Tombol Save PNG untuk QR tabel ===
    window.downloadQR = function(divId, filename) {
        const container = document.getElementById(divId);
        if (!container) return;

        let imgData = '';
        const imgTag = container.querySelector('img');
        const canvas = container.querySelector('canvas');

        // QRCode.js bisa menghasilkan canvas atau img, tergantung browser
        if (imgTag) imgData = imgTag.src;
        else if (canvas) imgData = canvas.toDataURL("image/png");

        if (!imgData) return alert('QR code belum siap.');

        const link = document.createElement('a');
        link.href = imgData;
        link.download = filename + "_QR.png";
        link.click();
    };

    // === GENERATE NOMOR BUKU TAMBAH ===
  const nomorAddInput = document.getElementById("nomor_buku_add");
  const btnGenerateAdd = document.getElementById("generateNomorBukuAdd");
  if (btnGenerateAdd && nomorAddInput) {
    // Hilangkan readonly supaya bisa diinput manual
    nomorAddInput.removeAttribute('readonly');

    btnGenerateAdd.addEventListener("click", function () {
      const rand = Math.floor(1000 + Math.random() * 9000);
      const year = new Date().getFullYear();
      nomorAddInput.value = "BK-" + year + rand;
    });
  }

  // === GENERATE QR TAMBAH ===
  const btnGenerateQRAdd = document.getElementById('btnGenerateQRAdd');
  if (btnGenerateQRAdd) {
    btnGenerateQRAdd.addEventListener('click', function () {
      const nomorInput = nomorAddInput.value;
      if (!nomorInput) return alert('Isi nomor buku dulu!');

      const container = document.getElementById('qrAddPreview');
      container.innerHTML = '';
      new QRCode(container, { text: nomorInput, width: 100, height: 100 });
    });
  }

  // === GENERATE NOMOR BUKU EDIT ===
  document.querySelectorAll('.btn-generate-nomor-edit').forEach(button => {
    button.addEventListener('click', function () {
      const id = button.dataset.id;
      const input = document.getElementById('nomor_buku_edit' + id);
      if (!input) return;
      input.removeAttribute('readonly'); // biar bisa manual
      const rand = Math.floor(1000 + Math.random() * 9000);
      const year = new Date().getFullYear();
      input.value = "BK-" + year + rand;
    });
  });

  // === GENERATE QR EDIT ===
  document.querySelectorAll('.btn-generate-qr-edit').forEach(button => {
    button.addEventListener('click', function () {
      const id = button.dataset.id;
      const input = document.getElementById('nomor_buku_edit' + id);
      if (!input || !input.value) return alert('Isi nomor buku dulu!');

      const container = document.getElementById('qrEditPreview' + id);
      container.innerHTML = '';
      new QRCode(container, { text: input.value, width: 100, height: 100 });
    });
  });

  // === RESET MODAL ADD ===
  const addModal = document.getElementById('addModal');
  addModal.addEventListener('hidden.bs.modal', function () {
    addModal.querySelector('form').reset();
    document.getElementById('qrAddPreview').innerHTML = '';
  });

  // === RESET MODAL EDIT ===
  document.querySelectorAll('[id^="editModal"]').forEach(modal => {
    modal.addEventListener('hidden.bs.modal', function () {
      modal.querySelector('form').reset();
      const qrContainer = modal.querySelector('[id^="qrEditPreview"]');
      if (qrContainer) qrContainer.innerHTML = '';
    });
  });

});
</script>
@endsection
