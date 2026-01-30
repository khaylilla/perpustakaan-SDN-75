@extends('admin.layout')

@section('page-title', 'Manajemen Data Artikel')

@section('content')

<style>
    /* === THEME CUSTOMIZATION (RED, WHITE, BLUE) === */
    :root {
        --primary-blue: #003366;    /* Biru Formal */
        --accent-red: #d90429;      /* Merah Berani */
        --soft-blue: rgba(0, 51, 102, 0.05);
        --soft-red: rgba(217, 4, 41, 0.1);
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
        transition: transform 0.3s ease;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    .stat-card:hover { transform: translateY(-5px); }

    .stat-card .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .bg-soft-red { background: var(--soft-red); color: var(--accent-red); }
    .bg-soft-blue { background: var(--soft-blue); color: var(--primary-blue); }

    /* === MANAGEMENT CONTAINER === */
    .management-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: none;
        padding: 25px;
        margin-top: 20px;
    }

    /* === FILTER/SEARCH SECTION === */
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
        background: var(--primary-blue);
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
        padding: 15px;
        vertical-align: middle;
        font-size: 13.5px;
        color: #475569;
    }

    .artikel-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* === BADGES === */
    .badge-kat {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11px;
    }
    .kat-informasi { background: #e0f2fe; color: #0369a1; }
    .kat-berita { background: #fef2f2; color: #991b1b; }
    .kat-artikel { background: #dcfce7; color: #166534; }

    /* === BUTTONS === */
    .btn-red {
        background: var(--accent-red);
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

    .btn-red:hover {
        background: #b90422;
        color: white;
        box-shadow: 0 4px 12px rgba(217, 4, 41, 0.3);
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
        text-decoration: none;
    }
    .btn-edit { background: var(--soft-blue); color: var(--primary-blue); }
    .btn-delete { background: var(--soft-red); color: var(--accent-red); }
    .btn-view { background: #f1f5f9; color: #475569; }
    .btn-edit:hover { background: var(--primary-blue); color: white; }
    .btn-delete:hover { background: var(--accent-red); color: white; }

    /* === MODAL STYLE === */
    .modal-content { border-radius: 20px; border: none; overflow: hidden; }
    .modal-header { padding: 20px 30px; border: none; }
    .form-control, .form-select { border-radius: 10px; padding: 10px 15px; }
</style>

<div class="container-fluid py-3">

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="stat-card">
                <div class="icon-box bg-soft-blue">
                    <i class="bi bi-file-text"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <h6 class="mb-1 fw-bold">Manajemen Data Artikel</h6>
                        <p class="text-muted small mb-0">Kelola berita, pengumuman, dan artikel literasi sekolah.</p>
                    </div>
                    <div class="text-end">
                        <span class="fs-4 fw-bold text-dark">{{ $artikels->count() }}</span>
                        <small class="d-block text-muted" style="font-size: 10px; text-transform: uppercase;">Total Konten</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <button class="btn btn-red w-100 h-100 py-3" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle-fill fs-5"></i>
                <span class="fs-6">Tambah Artikel Baru</span>
            </button>
        </div>
    </div>

    <div class="management-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="bi bi-card-text me-2 text-primary"></i> Daftar Artikel & Berita</h5>
        </div>

        <div class="filter-section">
            <form action="{{ route('admin.dataartikel') }}" method="GET" class="row g-3">
                <div class="col-lg-10 col-md-9">
                    <div class="search-input-group">
                        <i class="bi bi-search"></i>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari judul artikel..." value="{{ request('keyword') }}">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <button type="submit" class="btn btn-primary w-100" style="background: var(--primary-blue); border: none;">
                        <i class="bi bi-funnel me-1"></i> Filter
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
                            <th>Media</th>
                            <th>Informasi Artikel</th>
                            <th>Kategori</th>
                            <th>Ringkasan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($artikels as $artikel)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                @if($artikel->foto)
                                    <img src="{{ asset('storage/' . $artikel->foto) }}" class="artikel-img" alt="foto">
                                @else
                                    <div class="artikel-img bg-light d-flex align-items-center justify-content-center text-muted" style="font-size: 10px;">No Image</div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $artikel->judul }}</div>
                                <small class="text-muted">{{ $artikel->subjudul ?? '-' }}</small>
                                @if($artikel->link)
                                <div class="mt-1">
                                    <a href="{{ $artikel->link }}" target="_blank" class="text-primary text-decoration-none" style="font-size: 11px;">
                                        <i class="bi bi-link-45deg"></i> Lihat Sumber
                                    </a>
                                </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'Informasi/Pengumuman' => 'kat-informasi',
                                        'Berita' => 'kat-berita',
                                        'Artikel' => 'kat-artikel'
                                    ];
                                @endphp
                                <span class="badge-kat {{ $badgeClass[$artikel->kategori] ?? 'kat-informasi' }}">
                                    {{ $artikel->kategori }}
                                </span>
                            </td>
                            <td style="max-width: 250px;">
                                <div class="text-truncate text-muted" style="font-size: 12px;">
                                    {{ Str::limit($artikel->isi, 80) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('artikel.show', $artikel->id) }}" target="_blank" class="action-btn btn-view" title="Lihat">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $artikel->id }}" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="action-btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $artikel->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <form method="POST" action="{{ route('admin.dataartikel.update', $artikel->id) }}" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header" style="background: var(--primary-blue);">
                                            <h5 class="modal-title fw-bold text-white">Edit Artikel</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Kategori</label>
                                                    <select name="kategori" class="form-select" required>
                                                        <option value="Informasi/Pengumuman" {{ $artikel->kategori == 'Informasi/Pengumuman' ? 'selected' : '' }}>Informasi/Pengumuman</option>
                                                        <option value="Berita" {{ $artikel->kategori == 'Berita' ? 'selected' : '' }}>Berita</option>
                                                        <option value="Artikel" {{ $artikel->kategori == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Judul</label>
                                                    <input type="text" name="judul" value="{{ $artikel->judul }}" class="form-control" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Sub Judul (Opsional)</label>
                                                    <input type="text" name="subjudul" value="{{ $artikel->subjudul }}" class="form-control" placeholder="Keterangan singkat...">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Isi Artikel</label>
                                                    <textarea name="isi" rows="5" class="form-control" required>{{ $artikel->isi }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Update Foto (Kosongkan jika tidak diubah)</label>
                                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white fw-bold px-4" style="background: var(--primary-blue);">Simpan Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Modal Delete --}}
                        <div class="modal fade" id="deleteModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST" action="{{ route('admin.dataartikel.destroy', $artikel->id) }}">
                                    @csrf @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <i class="bi bi-exclamation-circle text-danger display-1 mb-4"></i>
                                            <h4 class="fw-bold">Hapus Artikel?</h4>
                                            <p class="text-muted">Artikel "<strong>{{ $artikel->judul }}</strong>" akan dihapus secara permanen.</p>
                                            <div class="mt-4">
                                                <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger px-4">Ya, Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada artikel yang ditambahkan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('admin.dataartikel.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header text-white" style="background: var(--primary-blue);">
                    <h5 class="modal-title fw-bold">Buat Artikel Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Informasi/Pengumuman">Informasi/Pengumuman</option>
                                <option value="Berita">Berita</option>
                                <option value="Artikel">Artikel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul..." required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Sub Judul (Opsional)</label>
                            <input type="text" name="subjudul" class="form-control" placeholder="Keterangan singkat...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Isi Artikel</label>
                            <textarea name="isi" rows="6" class="form-control" placeholder="Tulis konten artikel di sini..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Unggah Foto Utama</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red px-4">Publikasikan Artikel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection