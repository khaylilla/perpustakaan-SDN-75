@extends('admin.layout')

@section('page-title', 'Peminjaman Buku')

@section('content')
<style>
    /* 1. ANIMATIONS DEFINITION */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes glow {
        0% { box-shadow: 0 0 5px rgba(247, 147, 30, 0.2); }
        50% { box-shadow: 0 0 20px rgba(247, 147, 30, 0.6); }
        100% { box-shadow: 0 0 5px rgba(247, 147, 30, 0.2); }
    }

    /* 2. LAYOUT & CARDS */
    .fade-in-element {
        animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    .action-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 24px;
        padding: 40px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Glass Effect decoration */
    .action-header::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(247, 147, 30, 0.1);
        border-radius: 50%;
        filter: blur(50px);
    }

    /* 3. ENHANCED SCAN BUTTON */
    .btn-scan-premium {
        background: var(--accent);
        color: #000 !important;
        padding: 14px 28px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: glow 3s infinite;
        border: none;
    }

    .btn-scan-premium:hover {
        transform: scale(1.05) translateY(-5px);
        background: #fff;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* 4. REFINED SEARCH BAR (Smaller Font & Sleek Look) */
    .control-card {
        background: white;
        border-radius: 20px;
        padding: 15px 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
    }

    .search-group-refined {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-group-refined i {
        position: absolute;
        left: 15px;
        color: #94a3b8;
        font-size: 14px;
    }

    .search-input-custom {
        padding: 10px 15px 10px 42px;
        font-size: 13px; /* Ukuran font lebih kecil */
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        width: 100%;
    }

    .search-input-custom:focus {
        background: #fff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(247, 147, 30, 0.1);
        outline: none;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 5px;
        display: block;
        letter-spacing: 0.5px;
    }

    /* 5. TABLE MICRO-INTERACTIONS */
    .table-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 25px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }

    tbody tr {
        transition: all 0.3s ease;
    }

    tbody tr:hover {
        background-color: #fcfcfd !important;
        transform: scale(1.002);
    }

    /* Custom Badge */
    .status-badge {
        font-size: 11px;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.3px;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-4">

    {{-- HERO SECTION --}}
    <div class="action-header fade-in-element">
        <div class="action-text">
            <h2 class="mb-1">Manajemen Peminjaman</h2>
            <p class="text-white-50 small mb-0">Sistem otomasi sirkulasi buku SDN 75 Kota Bengkulu</p>
        </div>
        <a href="{{ route('admin.riwayat.peminjaman.scan') }}" class="btn-scan-premium">
            <i class="bi bi-qr-code-scan"></i>
            <span>SCAN PINJAM</span>
        </a>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="control-card fade-in-element" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.riwayat.peminjaman.peminjaman') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <span class="filter-label">Pencarian Cepat</span>
                <div class="search-group-refined">
                    <i class="bi bi-search"></i>
                    <input type="text" name="keyword" class="search-input-custom" placeholder="Nama siswa, NPM, atau judul..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-md-3">
                <span class="filter-label">Filter Tanggal</span>
                <input type="date" id="filter-date" class="search-input-custom" style="padding-left: 15px;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100 shadow-sm" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 600;">
                    Terapkan
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.riwayat.peminjaman.pdf') }}" id="downloadPdf" class="btn btn-outline-danger w-100" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-pdf me-1"></i> Cetak PDF
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-card fade-in-element" style="animation-delay: 0.2s;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="mainTable">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">NO</th>
                        <th class="py-3" style="font-size: 11px; color: #94a3b8;">DATA ANGGOTA</th>
                        <th class="py-3" style="font-size: 11px; color: #94a3b8;">INFORMASI BUKU</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">MASA PINJAM</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">STATUS</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman->where('status', 'dipinjam') as $index => $p)
                        @php
                            $tanggalKembali = $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali) : null;
                            $telat = $tanggalKembali ? (\Carbon\Carbon::now()->gt($tanggalKembali)) : false;
                        @endphp
                        <tr data-date="{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}">
                            <td class="text-center fw-bold text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark mb-0" style="font-size: 14px;">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size: 11px;">NPM: {{ $p->npm }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary mb-0" style="font-size: 13px;">{{ $p->judul_buku }}</div>
                                <div class="text-muted" style="font-size: 11px;">ID: {{ $p->nomor_buku }} | {{ $p->jumlah ?? 1 }} Eks</div>
                            </td>
                            <td class="text-center">
                                <div class="fw-bold text-dark" style="font-size: 12px;">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</div>
                                <div class="text-danger fw-semibold" style="font-size: 10px;">Kembali: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</div>
                            </td>
                            <td class="text-center">
                                @if($telat)
                                    <span class="status-badge bg-danger text-white shadow-sm">Terlambat</span>
                                @else
                                    <span class="status-badge bg-warning text-dark shadow-sm">Dipinjam</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light btn-edit" 
                                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-npm="{{ $p->npm }}"
                                        data-judul="{{ $p->judul_buku }}" data-jumlah="{{ $p->jumlah ?? 1 }}" data-status="{{ $p->status }}"
                                        style="border-radius: 8px;">
                                        <i class="bi bi-pencil-fill text-primary"></i>
                                    </button>
                                    <form action="{{ route('destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light" style="border-radius: 8px;">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">Tidak ada buku yang sedang dipinjam</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Modal Logic
    const editButtons = document.querySelectorAll('.btn-edit');
    const editForm = document.getElementById('editForm');

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const data = this.dataset;
            editForm.action = `/admin/riwayat/peminjaman/update/${data.id}`;
            editForm.querySelector('input[name="nama"]').value = data.nama;
            editForm.querySelector('input[name="npm"]').value = data.npm;
            editForm.querySelector('input[name="judul_buku"]').value = data.judul;
            editForm.querySelector('input[name="jumlah"]').value = data.jumlah;
            editForm.querySelector('select[name="status"]').value = data.status;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // Client-side Filter Date
    const dateInput = document.getElementById('filter-date');
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        const rows = document.querySelectorAll('#mainTable tbody tr');
        
        rows.forEach(row => {
            if(row.cells.length < 2) return; // Skip empty row
            const rowDate = row.getAttribute('data-date');
            if (!selectedDate || rowDate === selectedDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // PDF Link Update
    const pdfBtn = document.getElementById('downloadPdf');
    pdfBtn.addEventListener('click', function(e) {
        const date = dateInput.value;
        if(date) {
            this.href = `{{ route('admin.riwayat.peminjaman.pdf') }}?filter_date=${date}`;
        }
    });
});
</script>
@endsection