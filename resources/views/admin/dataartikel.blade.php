@extends('admin.layout')

@section('page-title', 'Manajemen Data Artikel')

@section('content')
<style>
  /* === HEADER BOX === */
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
  }
  .info-box {
    background: linear-gradient(135deg, #f7931e, #ffa94d);
    color: white;
    border-radius: 16px;
    width: 260px;
    padding: 16px 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .info-box:hover { transform: translateY(-4px); }
  .info-box i { font-size: 32px; opacity: 0.8; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin: 3px 0 0; }

  /* === SEARCH BAR === */
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
  .search-bar i {
    color: #666;
    font-size: 18px;
  }
  .search-bar input {
    border: none;
    outline: none;
    flex: 1;
    font-size: 14px;
    padding-left: 10px;
  }
  .search-bar .btn {
    white-space: nowrap;
    font-size: 14px;
    border-radius: 8px;
    padding: 6px 14px;
  }

  /* === TABLE === */
  .table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
    margin-top: 10px;
    padding: 10px;
    max-height: 650px;
  }
  table {
    margin-bottom: 0;
    min-width: 1100px;
    width: 100%;
    border-collapse: collapse;
  }
  table th, table td {
    vertical-align: middle !important;
    text-align: center;
    font-size: 13px;
    padding: 10px 8px;
    border: 1px solid #ddd;
  }
  table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: 600;
  }
  .table tbody tr:hover { background-color: #f8f9ff; }

  /* === Badge Kategori === */
  .badge-kategori {
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
  }
  .badge-informasi { background-color: #3498db; color: white; }
  .badge-berita { background-color: #e74c3c; color: white; }
  .badge-artikel { background-color: #27ae60; color: white; }

  /* === ACTION ICONS === */
  .action-icons i {
    font-size: 18px;
    cursor: pointer;
    margin: 0 6px;
    transition: color 0.2s;
  }
  .action-icons .edit { color: #f39c12; }
  .action-icons .edit:hover { color: #d68910; }
  .action-icons .delete { color: #e74c3c; }
  .action-icons .delete:hover { color: #c0392b; }

  /* === BUTTON ADD === */
  .btn-add {
    background-color: #4a4ca4;
    color: #fff;
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 14px;
    transition: 0.2s;
  }
  .btn-add:hover { background-color: #3c3f91; color: #fff; }

  @media (max-width: 768px) {
    .info-boxes { flex-direction: column; align-items: stretch; }
    .info-box { width: 100%; }
  }
</style>

<div class="container-fluid">
  {{-- HEADER BAR --}}
  <div class="info-boxes">
    <a href="{{ route('admin.datakoleksi') }}" class="info-box">
      <div class="info-box-content">
        <h5>Manajemen Koleksi</h5>
        <p>Buku & Artikel</p>
      </div>
      <i class="bi bi-journal-bookmark-fill"></i>
    </a>
    <a href="{{ route('admin.dataartikel') }}" class="info-box">
      <div class="info-box-content">
        <h5>Manajemen Artikel</h5>
        <p>Artikel Informasi</p>
      </div>
      <i class="bi bi-file-text-fill"></i>
    </a>
    <button class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-plus-lg me-1"></i> Tambah Artikel
    </button>
  </div>

  {{-- SEARCH --}}
  <form action="{{ route('admin.dataartikel') }}" method="GET" class="search-bar mb-3">
    <i class="bi bi-search"></i>
    <input type="text" name="keyword" placeholder="Cari judul artikel..." value="{{ request('keyword') }}">
    <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
  </form>

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- TABLE --}}
  <div class="table-container">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>No</th>
          <th>Kategori</th>
          <th>Judul</th>
          <th>Sub Judul</th>
          <th>Isi</th>
          <th>Foto</th>
          <th>Link</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($artikels as $artikel)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>
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
          </td>
          <td style="text-align: left;"><strong>{{ $artikel->judul }}</strong></td>
          <td style="text-align: left;">{{ $artikel->subjudul ?? '-' }}</td>
          <td style="text-align: left;">{{ Str::limit($artikel->isi, 50) }}</td>
          <td>
            @if($artikel->foto)
              <img src="{{ asset('storage/' . $artikel->foto) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            @if($artikel->link)
              <a href="{{ $artikel->link }}" target="_blank" class="text-primary">
                <i class="bi bi-link-45deg"></i>
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td class="action-icons">
            <i class="bi bi-pencil-square edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $artikel->id }}" title="Edit"></i>
            <i class="bi bi-trash delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $artikel->id }}" title="Hapus"></i>
            <a href="{{ route('artikel.show', $artikel->id) }}" target="_blank" class="text-info" title="Lihat">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>

        {{-- Modal Edit --}}
        <div class="modal fade" id="editModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.dataartikel.update', $artikel->id) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="modal-content">
                <div class="modal-header bg-warning">
                  <h5 class="modal-title">Edit Artikel</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label>Kategori <span class="text-danger">*</span></label>
                      <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Informasi/Pengumuman" {{ $artikel->kategori == 'Informasi/Pengumuman' ? 'selected' : '' }}>Informasi/Pengumuman</option>
                        <option value="Berita" {{ $artikel->kategori == 'Berita' ? 'selected' : '' }}>Berita</option>
                        <option value="Artikel" {{ $artikel->kategori == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label>Judul <span class="text-danger">*</span></label>
                      <input type="text" name="judul" value="{{ $artikel->judul }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                      <label>Sub Judul (Opsional)</label>
                      <input type="text" name="subjudul" value="{{ $artikel->subjudul ?? '' }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                      <label>Link Artikel (Opsional)</label>
                      <input type="url" name="link" value="{{ $artikel->link ?? '' }}" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-md-12">
                      <label>Isi Artikel <span class="text-danger">*</span></label>
                      <textarea name="isi" rows="5" class="form-control" required>{{ $artikel->isi }}</textarea>
                    </div>
                    <div class="col-md-12">
                      <label>Foto (Opsional)</label>
                      <input type="file" name="foto" class="form-control" accept="image/*">
                      @if($artikel->foto)
                        <small class="text-muted">Foto saat ini: <a href="{{ asset('storage/' . $artikel->foto) }}" target="_blank">Lihat</a></small>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-warning text-white">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        {{-- Modal Hapus --}}
        <div class="modal fade" id="deleteModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.dataartikel.destroy', $artikel->id) }}">
              @csrf
              @method('DELETE')
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-danger">Hapus Artikel</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  Apakah kamu yakin ingin menghapus <strong>{{ $artikel->judul }}</strong>?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        @empty
          <tr><td colspan="8" class="text-muted">Belum ada artikel yang ditambahkan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="{{ route('admin.dataartikel.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Artikel</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label>Kategori <span class="text-danger">*</span></label>
              <select name="kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Informasi/Pengumuman">Informasi/Pengumuman</option>
                <option value="Berita">Berita</option>
                <option value="Artikel">Artikel</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Judul <span class="text-danger">*</span></label>
              <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Sub Judul (Opsional)</label>
              <input type="text" name="subjudul" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Link Artikel (Opsional)</label>
              <input type="url" name="link" class="form-control" placeholder="https://...">
            </div>
            <div class="col-md-12">
              <label>Isi Artikel <span class="text-danger">*</span></label>
              <textarea name="isi" rows="5" class="form-control" required></textarea>
            </div>
            <div class="col-md-12">
              <label>Foto (Opsional)</label>
              <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
