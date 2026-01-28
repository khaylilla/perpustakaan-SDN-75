@extends('admin.layout')

@section('page-title', 'Pengembalian Buku')

@section('content')
<style>
    /* 1. ANIMATIONS */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes glow-green {
        0% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.2); }
        50% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); }
        100% { box-shadow: 0 0 5px rgba(16, 185, 129, 0.2); }
    }

    .fade-in-element {
        animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    /* 2. HERO SECTION - SCAN ONLY */
    .action-header {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
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

    .action-header::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(52, 211, 153, 0.1);
        border-radius: 50%;
        filter: blur(50px);
    }

    .btn-scan-premium {
        background: #10b981;
        color: white !important;
        padding: 14px 28px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: glow-green 3s infinite;
        border: none;
    }

    .btn-scan-premium:hover {
        transform: scale(1.05) translateY(-5px);
        background: #ffffff;
        color: #065f46 !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* 3. REFINED CONTROL BAR */
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
        font-size: 13px; /* Font kecil sesuai request */
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        width: 100%;
    }

    .search-input-custom:focus {
        background: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
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

    /* 4. MODERN TABLE */
    .table-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 25px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }

    tbody tr { transition: all 0.3s ease; }
    tbody tr:hover {
        background-color: #f0fdf4 !important;
        transform: scale(1.002);
    }

    .status-badge {
        font-size: 11px;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        background: #dcfce7;
        color: #166534;
    }
</style>

<div class="container-fluid py-4">

    {{-- HERO SECTION --}}
    <div class="action-header fade-in-element">
        <div class="action-text">
            <h2 class="mb-1 fw-bold">Riwayat Pengembalian</h2>
            <p class="text-white-50 small mb-0">Manajemen data buku yang telah berhasil dikembalikan oleh anggota.</p>
        </div>
        <a href="{{ route('admin.riwayat.pengembalian.scankembali') }}" class="btn-scan-premium">
            <i class="bi bi-qr-code-scan"></i>
            <span>SCAN PENGEMBALIAN</span>
        </a>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="control-card fade-in-element" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.riwayat.pengembalian.pengembalian') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <span class="filter-label">Cari Data</span>
                <div class="search-group-refined">
                    <i class="bi bi-search"></i>
                    <input type="text" name="keyword" class="search-input-custom" placeholder="Nama, NPM, atau Judul Buku..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-md-3">
                <span class="filter-label">Tanggal Pinjam</span>
                <input type="date" id="filter-date" class="search-input-custom" style="padding-left: 15px;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100 shadow-sm" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 600;">
                    Filter
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.riwayat.pengembalian.pdfkembali') }}" id="downloadPdf" class="btn btn-outline-success w-100" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 600;">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Laporan
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-card fade-in-element" style="animation-delay: 0.2s;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tablePengembalian">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">NO</th>
                        <th class="py-3" style="font-size: 11px; color: #94a3b8;">IDENTITAS ANGGOTA</th>
                        <th class="py-3" style="font-size: 11px; color: #94a3b8;">BUKU & JUMLAH</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">TGL PINJAM</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">TGL KEMBALI</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">STATUS</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #94a3b8;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman->where('status', 'dikembalikan') as $index => $p)
                        <tr data-date="{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}">
                            <td class="text-center fw-bold text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $p->npm }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-success" style="font-size: 13px;">{{ $p->judul_buku }}</div>
                                <div class="text-muted" style="font-size: 11px;">No: {{ $p->nomor_buku }} | Kembali: <strong>{{ $p->jumlah_kembali ?? 0 }}</strong></div>
                            </td>
                            <td class="text-center small">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="text-center small fw-bold text-primary">
                                {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="status-badge">Selesai</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light btn-edit" 
                                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-npm="{{ $p->npm }}"
                                        data-judul="{{ $p->judul_buku }}" data-nomor="{{ $p->nomor_buku }}" 
                                        data-jumlah_kembali="{{ $p->jumlah_kembali ?? 0 }}" data-status="{{ $p->status }}"
                                        style="border-radius: 8px;">
                                        <i class="bi bi-pencil-fill text-primary"></i>
                                    </button>
                                    <form action="{{ route('admin.riwayat.pengembalian.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data pengembalian?')">
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
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-check-all fs-1 text-muted opacity-25 d-block mb-2"></i>
                                <div class="text-muted">Belum ada riwayat pengembalian.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf @method('PUT')
            <div class="modal-header bg-success text-white border-0 py-3" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title fw-bold">Edit Data Pengembalian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="filter-label">Nama Anggota</label>
                        <input type="text" name="nama" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">NPM</label>
                        <input type="text" name="npm" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">Jumlah Kembali</label>
                        <input type="number" name="jumlah_kembali" class="search-input-custom" style="padding-left:15px" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="filter-label">Status</label>
                        <select name="status" class="search-input-custom" style="padding-left:15px">
                            <option value="dikembalikan">Dikembalikan</option>
                            <option value="dipinjam">Dipinjam (Batalkan Pengembalian)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const data = this.dataset;
            const form = document.getElementById('editForm');
            form.action = `/admin/riwayat/pengembalian/update/${data.id}`;
            form.querySelector('input[name="nama"]').value = data.nama;
            form.querySelector('input[name="npm"]').value = data.npm;
            form.querySelector('input[name="jumlah_kembali"]').value = data.jumlah_kembali;
            form.querySelector('select[name="status"]').value = data.status;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        });
    });

    // Date Filter Logic
    const dateInput = document.getElementById('filter-date');
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        const rows = document.querySelectorAll('#tablePengembalian tbody tr');
        rows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            if (!selectedDate || rowDate === selectedDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
@endsection