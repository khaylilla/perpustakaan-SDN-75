@extends('admin.layout')

@section('page-title', 'Pengembalian Buku')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* 1. ANIMATIONS */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float-blue {
        0% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
        50% { transform: translateY(-20px) translateX(10px); opacity: 0.6; }
        100% { transform: translateY(0px) translateX(0px); opacity: 0.3; }
    }

    .fade-in-element {
        animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    /* 2. HERO SECTION */
    .action-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
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
        box-shadow: 0 10px 25px rgba(30, 58, 138, 0.2);
    }

    .action-header::before {
        content: '';
        position: absolute;
        width: 150px; height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -20px; left: 10%;
        animation: float-blue 8s infinite ease-in-out;
    }

    .btn-scan-premium {
        background: #ffffff;
        color: #1e3a8a !important;
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

    .filter-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #1e3a8a;
        margin-bottom: 6px;
        display: block;
        letter-spacing: 0.8px;
    }

    /* 4. TABLE STYLING */
    .table-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 25px rgba(0,0,0,0.04);
        border: 1px solid #e2e8f0;
    }

    .status-badge {
        font-size: 11px;
        padding: 6px 14px;
        border-radius: 12px;
        font-weight: 700;
        background: #dbeafe;
        color: #1e40af;
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
            <h2 class="mb-1 fw-bold text-white">Riwayat Pengembalian</h2>
            <p class="text-white-50 small mb-0">Monitor dan kelola inventaris buku yang telah diterima kembali.</p>
        </div>
        <a href="{{ route('admin.riwayat.pengembalian.scankembali') }}" class="btn-scan-premium">
            <i class="bi bi-qr-code-scan fs-5"></i>
            <span>SCAN PENGEMBALIAN</span>
        </a>
    </div>

    {{-- SEARCH & FILTER BAR --}}
    <div class="control-card fade-in-element" style="animation-delay: 0.1s;">
        <form action="{{ route('admin.riwayat.pengembalian.pengembalian') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <span class="filter-label">Kata Kunci</span>
                <div class="search-group-refined" style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 15px; top: 12px; color: #3b82f6;"></i>
                    <input type="text" name="keyword" class="search-input-custom" placeholder="Nama, ID Anggota, atau Judul..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-md-3">
                <span class="filter-label">Tanggal Transaksi</span>
                <input type="date" id="filter-date" class="search-input-custom" style="padding-left: 15px;">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 shadow-sm" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 700; background: #1e3a8a; border: none;">
                    Cari Data
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.riwayat.pengembalian.pdfkembali') }}" class="btn btn-outline-primary w-100" style="border-radius: 12px; padding: 10px; font-size: 13px; font-weight: 700; border: 2px solid #dbeafe;">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-card fade-in-element" style="animation-delay: 0.2s;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" id="tablePengembalian">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">ID</th>
                        <th class="py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">DATA ANGGOTA</th>
                        <th class="py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">DETAIL BUKU</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">PINJAM</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">KEMBALI</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">STATUS</th>
                        <th class="text-center py-3" style="font-size: 11px; color: #64748b; font-weight: 800;">OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman->where('status', 'dikembalikan') as $index => $p)
                        <tr data-date="{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}">
                            <td class="text-center fw-bold text-muted small">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->nama }}</div>
                                <div class="text-primary fw-semibold" style="font-size: 11px;">{{ $p->npm }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->judul_buku }}</div>
                                <div class="text-muted" style="font-size: 11px;">Kode: {{ $p->nomor_buku }} | Jml: <span class="badge text-primary" style="background:#eff6ff">{{ $p->jumlah ?? 0 }}</span></div>
                            </td>
                            <td class="text-center small text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/y') }}</td>
                            <td class="text-center small fw-bold text-primary">{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}</td>
                            <td class="text-center">
                                <span class="status-badge shadow-sm">Returned</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn-action-custom btn-edit-blue btn-edit" 
                                        data-id="{{ $p->id }}" 
                                        data-nama="{{ $p->nama }}" 
                                        data-npm="{{ $p->npm }}"
                                        data-jumlah="{{ $p->jumlah ?? 0 }}" 
                                        data-status="{{ $p->status }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form id="delete-form-{{ $p->id }}" action="{{ route('admin.riwayat.pengembalian.destroy', $p->id) }}" method="POST">
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
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted fw-bold">Belum ada riwayat pengembalian.</div>
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
            @csrf 
            @method('PUT')
            <div class="modal-header text-white border-0 py-3" style="border-radius: 20px 20px 0 0; background: #1e3a8a;">
                <h5 class="modal-title fw-bold">Penyesuaian Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="filter-label">Nama Lengkap Anggota</label>
                        <input type="text" name="nama" id="edit_nama" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">Identitas / NPM</label>
                        <input type="text" name="npm" id="edit_npm" class="search-input-custom" style="padding-left:15px" required>
                    </div>
                    <div class="col-md-6">
                        <label class="filter-label">Jumlah Buku</label>
                        <input type="number" name="jumlah_kembali" id="edit_jumlah" class="search-input-custom" style="padding-left:15px" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="filter-label">Update Status</label>
                        <select name="status" id="edit_status" class="search-input-custom" style="padding-left:15px">
                            <option value="dikembalikan">Sudah Dikembalikan</option>
                            <option value="dipinjam">Batalkan & Set Jadi Dipinjam</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="button" class="btn btn-light rounded-3 px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold" style="background: #1e3a8a; border: none;">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. EDIT MODAL LOGIC
    const editButtons = document.querySelectorAll('.btn-edit');
    const editForm = document.getElementById('editForm');
    const modalEdit = new bootstrap.Modal(document.getElementById('editModal'));

    editButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const npm = this.getAttribute('data-npm');
            const jumlah = this.getAttribute('data-jumlah');
            const status = this.getAttribute('data-status');

            // UPDATE ACTION URL SECARA DINAMIS
            editForm.action = `/admin/riwayat/pengembalian/update/${id}`;
            
            // ISI VALUE INPUT
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_npm').value = npm;
            document.getElementById('edit_jumlah').value = jumlah;
            document.getElementById('edit_status').value = status;

            modalEdit.show();
        });
    });

    // 2. DATE FILTER
    const dateInput = document.getElementById('filter-date');
    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        const rows = document.querySelectorAll('#tablePengembalian tbody tr');
        rows.forEach(row => {
            const rowDate = row.getAttribute('data-date');
            row.style.display = (!selectedDate || rowDate === selectedDate) ? '' : 'none';
        });
    });
});

// 3. SWEETALERT DELETE
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Riwayat?',
        text: "Data pengembalian akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1e3a8a',
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

// 4. SESSION SUCCESS ALERT
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