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
  }
  table {
    margin-bottom: 0;
    min-width: 850px;
    width: 100%;
    border-collapse: collapse;
  }
  table th, table td {
    vertical-align: middle !important;
    text-align: center;
    font-size: 14px;
    padding: 12px 10px;
    border: 1px solid #ddd;
  }
  table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: 600;
  }
  .table tbody tr:hover { background-color: #f8f9ff; }

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
    <input type="text" name="keyword" placeholder="Cari judul atau deskripsi artikel..." value="{{ request('keyword') }}">
    <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
  </form>

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- TABLE --}}
  <div class="table-container">
    <table class="table align-middle text-center mb-0">
      <thead>
        <tr>
          <th>No</th>
          <th>Judul Artikel</th>
          <th>Deskripsi</th>
          <th>Link Artikel</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($artikels as $artikel)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $artikel->judul }}</td>
          <td>{{ Str::limit($artikel->deskripsi, 80) }}</td>
          <td>
            <a href="{{ $artikel->link }}" target="_blank" class="text-primary text-decoration-underline">
              Lihat Artikel
            </a>
          </td>
          <td class="action-icons">
            <i class="bi bi-pencil-square edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $artikel->id }}"></i>
            <i class="bi bi-trash delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $artikel->id }}"></i>
          </td>
        </tr>

        {{-- Modal Edit --}}
        <div class="modal fade" id="editModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.dataartikel.update', $artikel->id) }}">
              @csrf
              @method('PUT')
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Edit Artikel</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label>Judul Artikel</label>
                    <input type="text" name="judul" value="{{ $artikel->judul }}" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-control" required>{{ $artikel->deskripsi }}</textarea>
                  </div>
                  <div class="mb-3">
                    <label>Link Artikel</label>
                    <input type="url" name="link" value="{{ $artikel->link }}" class="form-control" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-warning">Simpan</button>
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
          <tr><td colspan="5" class="text-muted">Belum ada artikel yang ditambahkan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.dataartikel.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Artikel</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Judul Artikel</label>
            <input type="text" name="judul" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label>Link Artikel</label>
            <input type="url" name="link" class="form-control" required placeholder="https://">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
