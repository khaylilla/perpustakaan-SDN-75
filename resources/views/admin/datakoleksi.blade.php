@extends('admin.layout')

@section('page-title', 'Manajemen Data Koleksi')

@section('content')

<style>
    /* === THEME CUSTOMIZATION === */
    :root {
        --primary-navy: #020617;
        --accent-gold: #f7931e;
        --soft-gold: rgba(247, 147, 30, 0.1);
        --white: #ffffff;
    }

    /* === STAT CARDS === */
    .stat-card {
        background: var(--white);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        color: inherit;
    }

    .stat-card .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .bg-soft-gold { background: var(--soft-gold); color: var(--accent-gold); }

    /* === MANAGEMENT CONTAINER === */
    .management-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: none;
        padding: 25px;
        margin-top: 20px;
    }

    /* === FILTER SECTION === */
    .filter-section {
        background: #f8fafc;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e2e8f0;
    }

    .search-input-group {
        position: relative;
        flex-grow: 1;
    }

    .search-input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-group input {
        padding-left: 45px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    /* === TABLE STYLE === */
    .custom-table-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .table thead {
        background: var(--primary-navy);
        color: white;
    }

    .table thead th {
        font-weight: 500;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        padding: 15px;
        border: none;
    }

    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 13.5px;
        color: #475569;
    }

    .cover-img {
        width: 50px;
        height: 70px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* === BADGES === */
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 11px;
    }
    .status-tersedia { background: #dcfce7; color: #166534; }
    .status-dipinjam { background: #fee2e2; color: #991b1b; }
    .bg-soft-navy { background: rgba(2, 6, 23, 0.05); color: var(--primary-navy); }

    /* === BUTTONS === */
    .btn-gold {
        background: var(--accent-gold);
        color: white;
        border: none;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-gold:hover {
        background: #e68516;
        color: white;
        box-shadow: 0 4px 12px rgba(247, 147, 30, 0.3);
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 2px;
        transition: 0.3s;
    }
    .btn-edit { background: #fef3c7; color: #d97706; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-edit:hover { background: #fcd34d; }
    .btn-delete:hover { background: #fecaca; }

    /* === MODAL STYLE === */
    .modal-content { border-radius: 20px; border: none; overflow: hidden; }
    .modal-header { border-bottom: none; padding: 25px 30px; }
    .modal-body { padding: 0 30px 30px 30px; }
    .form-label { font-weight: 500; font-size: 14px; color: #475569; }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px var(--soft-gold);
    }
</style>

<div class="container-fluid py-3">

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="stat-card">
                <div class="icon-box bg-soft-gold">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h6 class="mb-1 fw-bold">Manajemen Data Koleksi</h6>
                        <p class="text-muted small mb-0">Kelola buku, skripsi, dan referensi perpustakaan Anda di sini.</p>
                    </div>
                    <div class="text-end">
                        <span class="fs-4 fw-bold text-dark">{{ $books->total() }}</span>
                        <small class="d-block text-muted" style="font-size: 10px; text-transform: uppercase;">Total Koleksi</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <button class="btn btn-gold w-100 h-100 py-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle-fill fs-5"></i>
                <span class="fs-6">Tambah Koleksi Baru</span>
            </button>
        </div>
    </div>

    <div class="management-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-layers me-2 text-warning"></i> Daftar Koleksi Perpustakaan</h5>
            <a href="{{ route('admin.datakoleksi.pdf', request()->all()) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
        </div>

        <div class="filter-section">
            <form action="{{ route('admin.datakoleksi') }}" method="GET" class="row g-3">
                <div class="col-lg-4 col-md-12">
                    <div class="search-input-group">
                        <i class="bi bi-search"></i>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari judul, penulis, barcode..." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="filter_kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($allKategori as $kat)
                            <option value="{{ $kat }}" {{ request('filter_kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <select name="filter_status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('filter_status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipinjam" {{ request('filter_status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-4">
                    <input type="date" name="filter_tanggal" class="form-control" value="{{ request('filter_tanggal') }}">
                </div>
                <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="custom-table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Cover</th>
                            <th>Informasi Buku</th>
                            <th>Kategori</th>
                            <th>Lokasi/Rak</th>
                            <th>Status</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td class="text-center text-muted">{{ ($books->currentPage()-1) * $books->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($book->cover)
                                    @php $covers = json_decode($book->cover, true); @endphp
                                    <img src="{{ asset('storage/' . ($covers[0] ?? '')) }}" class="cover-img" alt="cover">
                                @else
                                    <div class="cover-img bg-light d-flex align-items-center justify-content-center text-muted" style="font-size: 10px;">No Image</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $book->judul }}</div>
                                <div class="text-muted" style="font-size: 12px;">
                                    <i class="bi bi-person me-1"></i>{{ $book->penulis }} | <i class="bi bi-building me-1"></i>{{ $book->penerbit }}
                                </div>
                                <div class="mt-1">
                                    <span class="badge bg-light text-dark border" style="font-size: 10px;">{{ $book->barcode }}</span>
                                </div>
                            </td>
                            <td><span class="badge bg-soft-navy text-navy">{{ $book->kategori }}</span></td>
                            <td><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $book->rak }}</td>
                            <td>
                                <span class="badge-status {{ $book->status == 'Tersedia' ? 'status-tersedia' : 'status-dipinjam' }}">
                                    {{ $book->status ?? 'Tersedia' }}
                                </span>
                            </td>
                            <td><span class="fw-bold">{{ $book->jumlah }}</span> <small class="text-muted">eks</small></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="javascript:void(0)" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $book->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.datakoleksi.delete', $book->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete border-0" onclick="return confirm('Hapus buku ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data koleksi yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Koleksi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datakoleksi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Cover</label>
                            <input type="file" name="cover[]" class="form-control" multiple>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Barcode / ISBN (Wajib)</label>
                            <input type="text" name="barcode" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" list="kategoriList">
                            <datalist id="kategoriList">
                                @foreach($allKategori as $kat) <option value="{{ $kat }}"> @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" min="0" value="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rak</label>
                            <input type="text" name="rak" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Dipinjam">Dipinjam</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-gold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($books as $book)
<div class="modal fade" id="editModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">Edit: {{ $book->judul }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datakoleksi.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ganti Cover (Biarkan kosong jika tidak diubah)</label>
                            <input type="file" name="cover[]" class="form-control" multiple>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Barcode / ISBN</label>
                            <input type="text" name="barcode" class="form-control" value="{{ $book->barcode }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" value="{{ $book->judul }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="penulis" class="form-control" value="{{ $book->penulis }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control" value="{{ $book->penerbit }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control" value="{{ $book->tahun_terbit }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="{{ $book->kategori }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control" value="{{ $book->jumlah }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rak</label>
                            <input type="text" name="rak" class="form-control" value="{{ $book->rak }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Tersedia" {{ $book->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Dipinjam" {{ $book->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection