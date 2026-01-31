@extends('admin.layout')

@section('page-title', 'Manajemen Data Koleksi')

@section('content')

<style>
    /* === UNIQUE THEME CONFIGURATION (BLUE, RED, WHITE) === */
    :root {
        --theme-blue: #122142;
        --theme-blue-light: #334155;
        --theme-red: #e11d48;
        --theme-red-soft: #ffe4e6;
        --theme-white: #ffffff;
        --theme-bg: #f1f5f9;
        --theme-border: #e2e8f0;
    }

    body {
        background-color: var(--theme-bg);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* === HERO ANIMATION & GRADIENT === */
    .hero-section {
        background: linear-gradient(-45deg, #020617, #0f172a, #1e3a8a, #be123c);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        border-radius: 20px;
        padding: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
        margin-bottom: 25px;
        z-index: 1;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.4;
        z-index: -1;
        animation: floatBlob 8s infinite ease-in-out alternate;
    }

    .blob-1 { width: 150px; height: 150px; background: var(--theme-red); top: -20px; right: -20px; }
    .blob-2 { width: 100px; height: 100px; background: #3b82f6; bottom: -20px; left: 20%; animation-delay: -2s; }

    @keyframes floatBlob {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(20px, 40px) scale(1.1); }
    }

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

    /* === ACTION CARD === */
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
    }

    .action-card-btn:hover {
        border-color: var(--theme-red);
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(225, 29, 72, 0.1);
    }

    .icon-add {
        width: 50px; height: 50px;
        background: var(--theme-red-soft);
        color: var(--theme-red);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 10px;
        transition: 0.3s;
    }

    /* === TABLE & CONTENT === */
    .content-wrapper {
        background: var(--theme-white);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--theme-border);
        overflow: hidden;
    }

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

    .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }

    .table-custom thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 16px 24px;
        border-bottom: 2px solid var(--theme-border);
    }

    .table-custom tbody td { padding: 15px 24px; border-bottom: 1px solid var(--theme-border); }

    .book-thumb {
        width: 45px; height: 65px;
        border-radius: 6px;
        object-fit: cover;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* === BADGES === */
    .status-badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-tersedia { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .badge-dipinjam { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }

    /* === ACTIONS === */
    .btn-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s; border: none;
    }
    .btn-edit-soft { background: #eff6ff; color: #3b82f6; }
    .btn-del-soft { background: #fef2f2; color: #ef4444; }
    .btn-edit-soft:hover { background: #3b82f6; color: white; }
    .btn-del-soft:hover { background: #ef4444; color: white; }

    /* === MODAL === */
    .modal-custom .modal-content { border-radius: 20px; border: none; }
    .modal-header-custom { background: var(--theme-blue); color: white; padding: 20px 30px; }
    .form-label-custom { font-weight: 600; color: var(--theme-blue); font-size: 0.85rem; }
    .form-control-custom { border-radius: 10px; padding: 10px; border: 1px solid #cbd5e1; }
    .btn-submit-custom { background: var(--theme-red); color: white; border: none; border-radius: 10px; padding: 10px 25px; font-weight: 600; }
</style>

<div class="container-fluid py-4">
    <div class="row g-4 mb-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="hero-section h-100 d-flex flex-column justify-content-center">
                <div class="hero-blob blob-1"></div>
                <div class="hero-blob blob-2"></div>
                <i class="bi bi-book hero-pattern"></i>

                <div class="position-relative z-2">                    
                    <h2 class="fw-bold mb-3">Manajemen Data Koleksi</h2>
                    <div class="d-flex align-items-end gap-3 mt-auto">
                        <div>
                            <span class="hero-stat-number">{{ $books->total() }}</span>
                        </div>
                        <div class="mb-3 border-start border-white border-opacity-50 ps-3">
                            <span class="d-block text-white fw-bold">Total Koleksi</span>
                            <span class="d-block text-white-50 small">Buku, Skripsi & Referensi Digital</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="action-card-btn" data-bs-toggle="modal" data-bs-target="#addModal">
                <div class="icon-add"><i class="bi bi-plus-lg"></i></div>
                <h5 class="fw-bold text-dark">Tambah Koleksi</h5>
                <p class="text-muted small mb-0">Klik untuk input data buku baru</p>
            </div>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="search-container">
            <form action="{{ route('admin.datakoleksi') }}" method="GET" id="searchForm">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="position-relative">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" name="keyword" id="mainSearch" class="form-control custom-search-input" placeholder="Scan barcode atau cari judul..." value="{{ request('keyword') }}" autofocus autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="filter_kategori" class="form-select custom-search-input py-2">
                            <option value="">Semua Kategori</option>
                            @foreach($allKategori as $kat)
                                <option value="{{ $kat }}" {{ request('filter_kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100 h-100 rounded-pill fw-bold" style="background: var(--theme-blue);">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.datakoleksi.pdf', request()->all()) }}" target="_blank" class="btn btn-outline-danger w-100 h-100 rounded-pill fw-bold d-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
                        </a>
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
                        <th class="ps-4">No</th>
                        <th>Cover</th>
                        <th>Info Koleksi</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Status</th>
                        <th>Stok</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="ps-4 text-muted small">{{ ($books->currentPage()-1) * $books->perPage() + $loop->iteration }}</td>
                        <td>
                            @if($book->cover)
                                @php $covers = json_decode($book->cover, true); @endphp
                                <img src="{{ asset('storage/' . ($covers[0] ?? '')) }}" class="book-thumb" alt="cover">
                            @else
                                <div class="book-thumb bg-light d-flex align-items-center justify-content-center text-muted small border">N/A</div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $book->judul }}</div>
                            <div class="small text-muted">
                                {{ $book->penulis }} | {{ $book->barcode }}
                                @if($book->ebook_file)
                                    <span class="ms-1 badge bg-info text-white"><i class="bi bi-cloud-download"></i> E-book</span>
                                @endif
                            </div>
                        </td>
                        <td><span class="badge bg-light text-primary border">{{ $book->kategori }}</span></td>
                        <td><i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $book->rak }}</td>
                        <td>
                            <span class="status-badge {{ $book->status == 'Tersedia' ? 'badge-tersedia' : 'badge-dipinjam' }}">
                                <i class="bi {{ $book->status == 'Tersedia' ? 'bi-check-circle' : 'bi-x-circle' }}"></i> {{ $book->status ?? 'Tersedia' }}
                            </span>
                        </td>
                        <td class="fw-bold">{{ $book->jumlah }} <small class="text-muted">eks</small></td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-icon btn-edit-soft" data-bs-toggle="modal" data-bs-target="#editModal{{ $book->id }}"><i class="bi bi-pencil-fill"></i></button>
                                <form action="{{ route('admin.datakoleksi.delete', $book->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-del-soft" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash-fill"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Data koleksi tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $books->links() }}</div>
    </div>
</div>

<div class="modal fade modal-custom" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('admin.datakoleksi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Koleksi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Upload Cover</label>
                            <input type="file" name="cover[]" class="form-control form-control-custom" multiple accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Barcode / ISBN (Scan Alat di Sini)</label>
                            <input type="text" name="barcode" id="barcode_input_add" class="form-control form-control-custom barcode-field" required placeholder="Gunakan alat scanner...">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label-custom">Upload E-book (PDF/EPUB)</label>
                            <input type="file" name="ebook_file" class="form-control form-control-custom" accept=".pdf,.epub">
                            <small class="text-muted">Kosongkan jika hanya buku fisik.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">Judul Buku</label>
                            <input type="text" name="judul" class="form-control form-control-custom" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">Deskripsi / Sinopsis</label>
                            <textarea name="deskripsi" class="form-control form-control-custom" rows="4" placeholder="Masukkan ringkasan cerita atau detail buku..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Penulis</label>
                            <input type="text" name="penulis" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Kategori</label>
                            <input type="text" name="kategori" class="form-control form-control-custom" list="kategoriList">
                            <datalist id="kategoriList">
                                @foreach($allKategori as $kat) <option value="{{ $kat }}"> @endforeach
                            </datalist>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Jumlah Stok</label>
                            <input type="number" name="jumlah" class="form-control form-control-custom" min="0" value="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Lokasi Rak</label>
                            <input type="text" name="rak" class="form-control form-control-custom">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Status Awal</label>
                            <select name="status" class="form-select form-control-custom">
                                <option value="Tersedia">Tersedia</option>
                                <option value="Dipinjam">Dipinjam</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit-custom rounded-pill">Simpan Koleksi</button>
                </div>
            </div>
        </form>
    </div>
</div>

@foreach($books as $book)
<div class="modal fade modal-custom" id="editModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('admin.datakoleksi.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Koleksi: {{ Str::limit($book->judul, 20) }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Update Cover</label>
                            <input type="file" name="cover[]" class="form-control form-control-custom" multiple accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin ganti cover.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Barcode / ISBN (Scan Alat di Sini)</label>
                            <input type="text" name="barcode" class="form-control form-control-custom barcode-field" value="{{ $book->barcode }}" required placeholder="Gunakan alat scanner...">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label-custom">Update File E-book (PDF/EPUB)</label>
                            <input type="file" name="ebook_file" class="form-control form-control-custom" accept=".pdf,.epub">
                            @if($book->ebook_file)
                                <small class="text-success"><i class="bi bi-file-earmark-check"></i> File saat ini: {{ basename($book->ebook_file) }}</small>
                            @else
                                <small class="text-muted">Kosongkan jika tidak ada file digital.</small>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">Judul Buku</label>
                            <input type="text" name="judul" class="form-control form-control-custom" value="{{ $book->judul }}" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label-custom">Deskripsi / Sinopsis</label>
                            <textarea name="deskripsi" class="form-control form-control-custom" rows="4" placeholder="Masukkan ringkasan cerita...">{{ $book->deskripsi }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Penulis</label>
                            <input type="text" name="penulis" class="form-control form-control-custom" value="{{ $book->penulis }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control form-control-custom" value="{{ $book->penerbit }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control form-control-custom" value="{{ $book->tahun_terbit }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Kategori</label>
                            <input type="text" name="kategori" class="form-control form-control-custom" list="kategoriListEdit{{ $book->id }}" value="{{ $book->kategori }}">
                            <datalist id="kategoriListEdit{{ $book->id }}">
                                @foreach($allKategori as $kat) <option value="{{ $kat }}"> @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-custom">Jumlah Stok</label>
                            <input type="number" name="jumlah" class="form-control form-control-custom" min="0" value="{{ $book->jumlah }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Lokasi Rak</label>
                            <input type="text" name="rak" class="form-control form-control-custom" value="{{ $book->rak }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Status</label>
                            <select name="status" class="form-select form-control-custom">
                                <option value="Tersedia" {{ $book->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Dipinjam" {{ $book->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-submit-custom rounded-pill">Update Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    // 1. Logika Fokus Scanner Alat Fisik
    document.querySelectorAll('.barcode-field').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Biar nggak langsung submit form kalau kescan
                // Fokus pindah ke input Judul setelah scan
                const form = this.form;
                const index = Array.prototype.indexOf.call(form, this);
                if(form.elements[index + 2]) form.elements[index + 2].focus();
            }
        });
    });

    // 2. Auto Focus Barcode saat Modal Tambah dibuka
    const addModal = document.getElementById('addModal');
    if(addModal) {
        addModal.addEventListener('shown.bs.modal', function () {
            document.getElementById('barcode_input_add').focus();
        });
    }

    // 3. Live Search (Debounce 1 detik)
    let searchTimer;
    document.getElementById('mainSearch').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 1000);
    });
</script>

@endsection