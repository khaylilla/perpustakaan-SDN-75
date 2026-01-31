@extends('admin.layout')

@section('page-title', 'Peminjaman Buku')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. ANIMATIONS & EFFECTS */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float-hero {
        0% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
        50% { transform: translateY(-20px) translateX(10px); opacity: 0.6; }
        100% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
    }

    .fade-in-element {
        animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    /* 2. HERO SECTION - BLUE GRADIENT (Matched with Pengembalian) */
    .action-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
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
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.2);
    }

    .action-header::before {
        content: '';
        position: absolute;
        width: 150px; height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -20px; left: 10%;
        animation: float-hero 8s infinite ease-in-out;
    }

    .btn-scan-premium {
        background: #ffffff;
        color: #1e40af !important;
        padding: 14px 28px;
        border-radius: 16px;
        font-weight: 800;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        gap: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        z-index: 5;
    }

    .btn-scan-premium:hover {
        transform: scale(1.05) translateY(-3px);
        background: #f0f9ff;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* 3. CONTROL BAR */
    .control-card {
        background: white;
        border-radius: 20px;
        padding: 20px 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
    }

    .search-input-custom {
        padding: 10px 15px 10px 42px;
        font-size: 13px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: all 0.3s ease;
        width: 100%;
    }

    .search-input-custom:focus {
        background: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #1e3a8a;
        margin-bottom: 6px;
        display: block;
        letter-spacing: 0.8px;
    }

    /* 4. MODERN TABLE */
    .table-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 25px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
    }

    .table thead { background: #f1f5f9; }

    tbody tr:hover { background-color: #f8fafc !important; }

    .status-badge-active {
        font-size: 11px;
        padding: 6px 14px;
        border-radius: 12px;
        font-weight: 700;
        background: #dcfce7;
        color: #166534;
        text-transform: uppercase;
    }

    .status-badge-overdue {
        font-size: 11px;
        padding: 6px 14px;
        border-radius: 12px;
        font-weight: 700;
        background: #fee2e2;
        color: #991b1b;
        text-transform: uppercase;
    }

    .btn-action-custom {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.3s;
        border: none;
    }

    .btn-edit-blue { background: #eff6ff; color: #2563eb; }
    .btn-edit-blue:hover { background: #2563eb; color: white; }
    .btn-delete-red { background: #fef2f2; color: #dc2626; }
    .btn-delete-red:hover { background: #dc2626; color: white; }
</style>

<div class="container-fluid py-4">

    {{-- HERO SECTION --}}
    <div class="action-header fade-in-element">
        <div class="action-text" style="z-index: 2;">
            <h2 class="mb-1 fw-bold text-white">Manajemen Peminjaman</h2>
            <p class="text-white-50 small mb-0">Pantau sirkulasi buku yang sedang dipinjam oleh anggota.</p>
        </div>
        <a href="{{ route('admin.riwayat.peminjaman.scan') }}" class="btn-scan-premium">
            <i class="bi bi-qr-code-scan fs-5"></i>
            <span>SCAN PEMINJAMAN</span>
        </a>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="control-card fade-in-element" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.riwayat.peminjaman.peminjaman') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <span class="filter-label">Cari Peminjam / Buku</span>
                <div style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 15px; top: 12px; color: #3b82f6;"></i>
                    <input type="text" name="keyword" class="search-input-custom" placeholder="Nama, NPM, atau Judul..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-md-3">
                <span class="filter-label">Tanggal Pinjam</span>
                <input type="date" id="filter-date" class="search-input-custom" style="padding-left: 15px;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 shadow-sm" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 700; background: #1e40af; border: none;">
                    Cari Data
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.riwayat.peminjaman.pdf') }}" id="downloadPdf" class="btn btn-outline-danger w-100" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 700; border: 2px solid #fee2e2; color: #dc2626;">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> CETAK PDF
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-card fade-in-element" style="animation-delay: 0.2s;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="mainTable">
                <thead>
                    <tr>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">NO</th>
                        <th class="py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">DATA PEMINJAM</th>
                        <th class="py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">DETAIL BUKU</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">PINJAM / TENGGAT</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">STATUS</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman->where('status', 'dipinjam') as $index => $p)
                        @php
                            $tenggat = $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali) : null;
                            $isOverdue = $tenggat ? \Carbon\Carbon::now()->gt($tenggat) : false;
                        @endphp
                        <tr data-date="{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}">
                            <td class="text-center fw-bold text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->nama }}</div>
                                <div class="text-primary fw-semibold" style="font-size: 11px;">{{ $p->npm }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->judul_buku }}</div>
                                <div class="text-muted" style="font-size: 11px;">Kode: {{ $p->nomor_buku }} | Jml: {{ $p->jumlah ?? 1 }}</div>
                            </td>
                            <td class="text-center">
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/y') }}</div>
                                <div class="fw-bold text-danger" style="font-size: 11px;">Hingga: {{ $tenggat ? $tenggat->format('d/m/y') : '-' }}</div>
                            </td>
                            <td class="text-center">
                                @if($isOverdue)
                                    <span class="status-badge-overdue shadow-sm">Overdue</span>
                                @else
                                    <span class="status-badge-active shadow-sm">Active</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn-action-custom btn-edit-blue btn-edit" 
                                        data-id="{{ $p->id }}" data-nama="{{ $p->nama }}" data-npm="{{ $p->npm }}"
                                        data-judul="{{ $p->judul_buku }}" data-jumlah="{{ $p->jumlah ?? 1 }}" data-status="{{ $p->status }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.riwayat.peminjaman.destroy', $p->id) }}" method="POST" id="delete-form-{{ $p->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-action-custom btn-delete-red" onclick="confirmDelete({{ $p->id }})">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted fw-bold">Tidak ada peminjaman aktif saat ini.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT (Identical with Pengembalian style) --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            @csrf @method('PUT')
            <div class="modal-header text-white border-0 py-3" style="border-radius: 20px 20px 0 0; background: #1e40af;">
                <h5 class="modal-title fw-bold">Edit Sirkulasi Buku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="filter-label">Nama Peminjam</label>
                        <input type="text" name="nama" id="edit_nama" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">NPM</label>
                        <input type="text" name="npm" id="edit_npm" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">Jumlah</label>
                        <input type="number" name="jumlah" id="edit_jumlah" class="search-input-custom" style="padding-left:15px" readonly>
                    </div>
                    <div class="col-12">
                        <label class="filter-label">Judul Buku</label>
                        <input type="text" name="judul_buku" id="edit_judul" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-12">
                        <label class="filter-label">Status Peminjaman</label>
                        <select name="status" id="edit_status" class="search-input-custom" style="padding-left:15px">
                            <option value="dipinjam">Dipinjam (Aktif)</option>
                            <option value="dikembalikan">Selesaikan & Kembalikan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold" style="background: #1e40af; border: none;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Logika Modal Edit
    const editButtons = document.querySelectorAll('.btn-edit');
    const editForm = document.getElementById('editForm');
    const modalEdit = new bootstrap.Modal(document.getElementById('editModal'));

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            editForm.action = `/admin/riwayat/peminjaman/update/${d.id}`;
            document.getElementById('edit_nama').value = d.nama;
            document.getElementById('edit_npm').value = d.npm;
            document.getElementById('edit_judul').value = d.judul;
            document.getElementById('edit_jumlah').value = d.jumlah;
            document.getElementById('edit_status').value = d.status;
            modalEdit.show();
        });
    });

    // 2. Filter Tanggal
    const dateInput = document.getElementById('filter-date');
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        const rows = document.querySelectorAll('#mainTable tbody tr');
        rows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            row.style.display = (!selectedDate || rowDate === selectedDate) ? '' : 'none';
        });
    });

    // 3. Update Link PDF dengan Filter
    const pdfBtn = document.getElementById('downloadPdf');
    pdfBtn.addEventListener('click', function(e) {
        if(dateInput.value) {
            this.href = `{{ route('admin.riwayat.peminjaman.pdf') }}?filter_date=${dateInput.value}`;
        }
    });
});

// 4. Konfirmasi Hapus SweetAlert
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Data?',
        text: "Data peminjaman ini akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1e40af',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

// 5. Success Message
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false,
        customClass: { popup: 'rounded-4' }
    });
@endif
</script>
@endsection