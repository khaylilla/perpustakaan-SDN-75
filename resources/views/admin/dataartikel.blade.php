@extends('admin.layout')

@section('page-title', 'Manajemen Data Artikel')

@section('content')

<style>
    /* === UNIQUE THEME CONFIGURATION (BLUE, RED, WHITE) === */
    :root {
        --theme-blue: #122142;        /* Dark Blue - Elegan */
        --theme-blue-light: #334155;  /* Slate Blue */
        --theme-red: #e11d48;         /* Rose Red - Modern & Berani */
        --theme-red-soft: #ffe4e6;    /* Soft Red background */
        --theme-white: #ffffff;
        --theme-bg: #f1f5f9;          /* Light Grayish Blue Background */
        --theme-border: #e2e8f0;
    }

    body {
        background-color: var(--theme-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* === HEADER & STATS SECTION === */
    /* === HERO ANIMATION & GRADIENT === */
    .hero-section {
        /* Gradasi 4 warna: Biru Gelap, Biru Slate, Biru Formal, dan Aksen Merah */
        background: linear-gradient(-45deg, #020617, #0f172a, #1e3a8a, #be123c);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite; /* Animasi Background Bergerak */
        
        border-radius: 20px;
        padding: 30px;
        color: white;
        position: relative;
        overflow: hidden; /* Supaya elemen animasi tidak keluar kotak */
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        margin-bottom: 25px;
        z-index: 1;
    }

    /* Keyframes untuk Background Bergerak */
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Elemen Bulat Bercahaya (Blobs) */
    .hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.4;
        z-index: -1;
        animation: floatBlob 8s infinite ease-in-out alternate;
    }

    .blob-1 {
        width: 150px;
        height: 150px;
        background: var(--theme-red);
        top: -20px;
        right: -20px;
    }

    .blob-2 {
        width: 100px;
        height: 100px;
        background: #3b82f6;
        bottom: -20px;
        left: 20%;
        animation-delay: -2s;
    }

    @keyframes floatBlob {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(20px, 40px) scale(1.1); }
    }

    /* Icon Pattern yang Melayang */
    .hero-pattern {
        position: absolute;
        top: 10%; 
        right: 5%;
        opacity: 0.1;
        font-size: 8rem;
        transform: rotate(-15deg);
        color: white;
        animation: floatIcon 6s ease-in-out infinite;
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0) rotate(-15deg); }
        50% { transform: translateY(-20px) rotate(-10deg); }
    }

    .hero-stat-number {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(to right, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* === ACTION CARD (Add Button) === */
    .action-card-btn {
        background: white;
        border-radius: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed var(--theme-border);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        color: var(--theme-blue-light);
        position: relative;
        overflow: hidden;
    }

    .action-card-btn:hover {
        border-color: var(--theme-red);
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(225, 29, 72, 0.1);
    }

    .action-card-btn:hover .icon-add {
        background: var(--theme-red);
        color: white;
        transform: scale(1.1);
    }

    .icon-add {
        width: 50px;
        height: 50px;
        background: var(--theme-red-soft);
        color: var(--theme-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    /* === MAIN CONTENT CARD === */
    .content-wrapper {
        background: var(--theme-white);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--theme-border);
        overflow: hidden;
    }

    /* === SEARCH BAR === */
    .search-container {
        padding: 20px;
        background: white;
        border-bottom: 1px solid var(--theme-border);
    }

    .custom-search-input {
        background: var(--theme-bg);
        border: 1px solid transparent;
        border-radius: 50px;
        padding: 12px 20px 12px 45px;
        transition: all 0.3s;
    }

    .custom-search-input:focus {
        background: white;
        border-color: var(--theme-blue);
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* === TABLE STYLING === */
    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 16px 24px;
        border-bottom: 2px solid var(--theme-border);
    }

    .table-custom tbody td {
        padding: 20px 24px;
        vertical-align: middle;
        border-bottom: 1px solid var(--theme-border);
        color: #334155;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .article-thumb {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 2px solid white;
    }

    /* === BADGES === */
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .badge-info { background: #eff6ff; color: #3b82f6; border: 1px solid #dbeafe; }
    .badge-news { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; }
    .badge-article { background: #f0fdf4; color: #22c55e; border: 1px solid #dcfce7; }
    
    /* === ACTION BUTTONS === */
    .btn-action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }

    .btn-view-soft { background: #f1f5f9; color: #475569; }
    .btn-view-soft:hover { background: var(--theme-blue); color: white; }

    .btn-edit-soft { background: #eff6ff; color: #3b82f6; }
    .btn-edit-soft:hover { background: #3b82f6; color: white; }

    .btn-del-soft { background: #fef2f2; color: #ef4444; }
    .btn-del-soft:hover { background: #ef4444; color: white; }

    /* === MODAL CUSTOMIZATION === */
    .modal-custom .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(66, 62, 62, 0.25);
    }
    
    .modal-header-custom {
        background: var(--theme-blue);
        color: white;
        padding: 20px 30px;
    }

    .modal-body-custom {
        padding: 30px;
        background: #fff;
    }

    .form-label-custom {
        font-weight: 600;
        color: var(--theme-blue);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-control-custom {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #cbd5e1;
    }
    
    .form-control-custom:focus {
        border-color: var(--theme-blue);
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
    }

    .btn-submit-custom {
        background: var(--theme-red);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-submit-custom:hover {
        background: #be123c;
        box-shadow: 0 4px 12px rgba(225, 29, 72, 0.3);
    }
</style>

<div class="container-fluid py-4">

    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="hero-section h-100 d-flex flex-column justify-content-center">
                
                <div class="hero-blob blob-1"></div>
                <div class="hero-blob blob-2"></div>
                <i class="bi bi-newspaper hero-pattern"></i>

                <div class="position-relative z-2">                    
                    <h2 class="fw-bold mb-3" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Manajemen Konten & Artikel
                    </h2>
                    
                    <div class="d-flex align-items-end gap-3 mt-auto">
                        <div>
                            <span class="hero-stat-number" style="font-size: 3.5rem;">{{ $artikels->count() }}</span>
                        </div>
                        <div class="mb-3 border-start border-white border-opacity-50 ps-3">
                            <span class="d-block text-white fw-bold">Total Publikasi</span>
                            <span class="d-block text-white-50 small">Berita, Artikel & Pengumuman Sekolah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="action-card-btn" data-bs-toggle="modal" data-bs-target="#addModal">
                <div class="icon-add">
                    <i class="bi bi-plus-lg"></i>
                </div>
                <h5 class="fw-bold text-dark">Tambah Baru</h5>
                <p class="text-muted small mb-0">Klik untuk membuat artikel</p>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="search-container">
            <form action="{{ route('admin.dataartikel') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-9">
                        <div class="position-relative">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="keyword" class="form-control custom-search-input" placeholder="Cari judul artikel, topik, atau kata kunci..." value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-dark w-100 h-100 rounded-pill fw-bold" style="background: var(--theme-blue);">
                            Filter Data
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 m-4 d-flex align-items-center gap-3 shadow-sm rounded-3">
                <i class="bi bi-check-circle-fill fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Media</th>
                        <th>Detail Artikel</th>
                        <th>Kategori</th>
                        <th>Preview Isi</th>
                        <th class="text-center pe-4">Kontrol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($artikels as $artikel)
                    <tr>
                        <td class="ps-4">
                            @if($artikel->foto)
                                <img src="{{ asset('storage/' . $artikel->foto) }}" class="article-thumb" alt="Thumb">
                            @else
                                <div class="article-thumb bg-light d-flex align-items-center justify-content-center text-muted fw-bold border">
                                    <span style="font-size: 10px;">N/A</span>
                                </div>
                            @endif
                        </td>
                        <td style="min-width: 200px;">
                            <div class="fw-bold text-dark mb-1">{{ $artikel->judul }}</div>
                            @if($artikel->subjudul)
                                <div class="small text-muted mb-1"><i class="bi bi-quote me-1"></i>{{ $artikel->subjudul }}</div>
                            @endif
                            @if($artikel->link)
                                <a href="{{ $artikel->link }}" target="_blank" class="badge bg-light text-primary text-decoration-none border">
                                    <i class="bi bi-link-45deg"></i> Sumber Luar
                                </a>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = 'badge-info';
                                $icon = 'bi-megaphone';
                                if($artikel->kategori == 'Berita') { $badgeClass = 'badge-news'; $icon = 'bi-newspaper'; }
                                if($artikel->kategori == 'Artikel') { $badgeClass = 'badge-article'; $icon = 'bi-journal-text'; }
                            @endphp
                            <span class="status-badge {{ $badgeClass }}">
                                <i class="bi {{ $icon }}"></i> {{ $artikel->kategori }}
                            </span>
                        </td>
                        <td class="text-muted small" style="max-width: 300px;">
                            {{ Str::limit($artikel->isi, 90) }}
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-action-group">
                                <a href="{{ route('artikel.show', $artikel->id) }}" target="_blank" class="btn-icon btn-view-soft" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn-icon btn-edit-soft" data-bs-toggle="modal" data-bs-target="#editModal{{ $artikel->id }}" title="Edit">
                                    <i class="bi bi-pencil-fill" style="font-size: 0.9rem;"></i>
                                </button>
                                <button type="button" class="btn-icon btn-del-soft" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $artikel->id }}" title="Hapus">
                                    <i class="bi bi-trash-fill" style="font-size: 0.9rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Edit (Inside Loop) --}}
                    <div class="modal fade modal-custom" id="editModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <form method="POST" action="{{ route('admin.dataartikel.update', $artikel->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header-custom d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Konten</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body-custom">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label-custom">Kategori</label>
                                                <select name="kategori" class="form-select form-control-custom" required>
                                                    <option value="Informasi/Pengumuman" {{ $artikel->kategori == 'Informasi/Pengumuman' ? 'selected' : '' }}>Informasi/Pengumuman</option>
                                                    <option value="Berita" {{ $artikel->kategori == 'Berita' ? 'selected' : '' }}>Berita</option>
                                                    <option value="Artikel" {{ $artikel->kategori == 'Artikel' ? 'selected' : '' }}>Artikel</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label-custom">Judul Utama</label>
                                                <input type="text" name="judul" value="{{ $artikel->judul }}" class="form-control form-control-custom" required>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label-custom">Sub Judul (Opsional)</label>
                                                <input type="text" name="subjudul" value="{{ $artikel->subjudul }}" class="form-control form-control-custom">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label-custom">Isi Konten</label>
                                                <textarea name="isi" rows="6" class="form-control form-control-custom" required>{{ $artikel->isi }}</textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label-custom">Update Gambar</label>
                                                <div class="d-flex gap-3 align-items-center p-2 border rounded bg-light">
                                                    @if($artikel->foto)
                                                        <img src="{{ asset('storage/' . $artikel->foto) }}" width="50" height="50" class="rounded" style="object-fit: cover">
                                                    @endif
                                                    <input type="file" name="foto" class="form-control" accept="image/*">
                                                </div>
                                                <small class="text-muted">*Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-submit-custom rounded-pill">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Delete (Inside Loop) --}}
                    <div class="modal fade modal-custom" id="deleteModal{{ $artikel->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <form method="POST" action="{{ route('admin.dataartikel.destroy', $artikel->id) }}">
                                @csrf @method('DELETE')
                                <div class="modal-content text-center overflow-hidden">
                                    <div class="p-5">
                                        <div class="mb-4">
                                            <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-trash text-danger" style="font-size: 2.5rem;"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold text-dark">Hapus Artikel Ini?</h4>
                                        <p class="text-muted mb-4">Tindakan ini tidak dapat dibatalkan. Artikel <strong>"{{ Str::limit($artikel->judul, 20) }}"</strong> akan hilang dari database.</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-bold" data-bs-dismiss="modal">Batalkan</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold bg-gradient">Ya, Hapus Permanen</button>
                                        </div>
                                    </div>
                                    <div class="bg-light py-2 border-top">
                                        <small class="text-muted">System Security ID: #{{ $artikel->id }}</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-folder2-open display-4 text-muted mb-3 opacity-50"></i>
                                <h6 class="text-muted fw-bold">Data tidak ditemukan</h6>
                                <p class="text-muted small">Belum ada artikel yang ditambahkan atau hasil pencarian nihil.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah (Outside Loop) --}}
<div class="modal fade modal-custom" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" action="{{ route('admin.dataartikel.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header-custom d-flex justify-content-between align-items-center" style="background: var(--theme-blue);">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Buat Artikel Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori Konten</label>
                            <select name="kategori" class="form-select form-control-custom" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Informasi/Pengumuman">Informasi/Pengumuman</option>
                                <option value="Berita">Berita</option>
                                <option value="Artikel">Artikel</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Judul Utama</label>
                            <input type="text" name="judul" class="form-control form-control-custom" placeholder="Tulis judul yang menarik..." required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom">Sub Judul / Keterangan Singkat</label>
                            <input type="text" name="subjudul" class="form-control form-control-custom" placeholder="Opsional...">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom">Isi Artikel</label>
                            <textarea name="isi" rows="8" class="form-control form-control-custom" placeholder="Mulai menulis konten di sini..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-custom">Unggah Gambar Utama</label>
                            <div class="input-group">
                                <input type="file" name="foto" class="form-control form-control-custom" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit-custom rounded-pill">Publikasikan Sekarang</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection