@extends('admin.layout')

@section('page-title', 'Presensi & Kehadiran Anggota')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    :root {
        --primary-blue: #0d6efd;
        --deep-blue: #0a58ca;
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    body {
        font-size: 0.85rem;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .premium-header {
        position: relative;
        background: linear-gradient(-45deg, #1a1a2e, #16213e, #4e73df, #0a58ca);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        border-radius: 15px; /* Lebih kecil */
        padding: 25px 30px; /* Lebih ramping */
        color: white;
        overflow: hidden;
        margin-bottom: 30px; /* Lebih rapat */
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(5px);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        z-index: 1;
    }
    .shape-1 { width: 100px; height: 100px; top: -20px; right: 5%; }
    .shape-2 { width: 70px; height: 70px; bottom: -15px; left: 10%; }

    .stats-container {
        margin-top: -55px; /* Disesuaikan dengan header yang mengecil */
        position: relative;
        z-index: 10;
        padding: 0 15px;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); }

    .glass-section {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
    }

    /* Input & Button Lebih Kecil */
    .input-premium {
        border: 2px solid #f1f5f9;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    .input-premium:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .btn-sm-custom {
        padding: 8px 15px;
        font-size: 0.85rem;
        border-radius: 8px;
    }

    .badge-time {
        background: #f0f4ff;
        color: #4e73df;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    /* Tabel Lebih Rapat */
    .table thead th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table tbody td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }

    .icon-box-sm {
        width: 40px; 
        height: 40px;
        border-radius: 10px;
    }
</style>

<div class="container-fluid py-3" style="background: #f8fafc; min-height: 100vh;">

    <div class="premium-header">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div style="position: relative; z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1 text-white">Log Presensi Kehadiran</h3>
                    <p class="small mb-0 opacity-75">SDN 75 Kota Bengkulu — Monitoring Perpustakaan Digital</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-white btn-sm fw-bold px-3 py-2 rounded-2 shadow-sm" 
                            style="background: white; color: var(--deep-blue); border: none;"
                            data-bs-toggle="modal" data-bs-target="#createAbsenModal">
                        <i class="bi bi-plus-circle-fill me-1"></i>Absen Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-container row g-3">
        <div class="col-md-4">
            <a href="{{ route('admin.absen.scan') }}" class="text-decoration-none">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body py-2 px-3 d-flex align-items-center">
                        <div class="icon-box-sm bg-primary text-white d-flex align-items-center justify-content-center shadow-sm">
                            <i class="bi bi-qr-code-scan fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0 fw-bold text-dark small">Scanner QR</p>
                            <span style="font-size: 0.7rem;" class="text-muted">Gunakan Kamera</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body py-2 px-3 d-flex align-items-center">
                    <div class="icon-box-sm bg-success text-white d-flex align-items-center justify-content-center shadow-sm">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <p class="mb-0 fw-bold text-dark small">{{ $absens->count() }} Anggota</p>
                        <span style="font-size: 0.7rem;" class="text-muted">Kunjungan Hari Ini</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.kartu') }}" class="text-decoration-none">
                <div class="card stat-card border-0 shadow-sm">
                    <div class="card-body py-2 px-3 d-flex align-items-center">
                        <div class="icon-box-sm bg-info text-white d-flex align-items-center justify-content-center shadow-sm">
                            <i class="bi bi-person-badge fs-5"></i>
                        </div>
                        <div class="ms-3">
                            <p class="mb-0 fw-bold text-dark small">Cetak Kartu</p>
                            <span style="font-size: 0.7rem;" class="text-muted">Data Anggota</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="glass-section">
        <form action="{{ route('admin.dataabsen') }}" method="GET" class="row g-2 mb-4 align-items-end">
            <div class="col-md-3">
                <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">CARI NAMA</label>
                <input type="text" name="keyword" class="form-control input-premium" placeholder="Nama / NIP / NISN..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-2">
                <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">KATEGORI</label>
                <select name="type" class="form-select input-premium">
                    <option value="">Semua</option>
                    <option value="siswa" {{ request('type') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('type') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="fw-bold text-muted mb-1" style="font-size: 0.7rem;">RENTANG TANGGAL</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control input-premium py-1" value="{{ request('start_date') }}">
                    <span class="input-group-text bg-transparent border-0 px-1">-</span>
                    <input type="date" name="end_date" class="form-control input-premium py-1" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 btn-sm-custom fw-bold shadow-sm">Filter</button>
                <a href="{{ route('admin.dataabsen.export', ['groupBy' => 'day']) }}" class="btn btn-outline-danger btn-sm-custom px-3 shadow-sm">
                    <i class="bi bi-file-pdf"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted" style="border-bottom: 2px solid #f1f5f9;">
                        <th class="pb-2">NAMA ANGGOTA</th>
                        <th class="pb-2">IDENTITAS</th>
                        <th class="pb-2">WAKTU MASUK</th>
                        <th class="pb-2 text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absens as $absen)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-2 d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                    {{ substr($absen->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $absen->nama }}</div>
                                    <div style="font-size: 0.7rem;" class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Hadir</div>
                                </div>
                            </div>
                        </td>
                        <td><code class="px-2 py-1 bg-light rounded text-dark border" style="font-size: 0.75rem;">{{ $absen->npm }}</code></td>
                        <td><span class="badge-time">{{ $absen->created_at->format('H:i') }} WIB</span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <form action="{{ route('admin.dataabsen.delete', $absen->id) }}" method="POST">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-3 btn-delete" 
                                    title="Hapus Data"
                                    style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-trash" style="font-size: 0.9rem;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <span class="text-muted small">Tidak ada data kehadiran ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createAbsenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                <h6 class="fw-bold mb-0">Input Presensi Manual</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="font-size: 0.7rem;"></button>
            </div>
            <form action="{{ route('admin.dataabsen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.7rem;">PILIH ANGGOTA</label>
                        <select name="person_id" id="searchAnggota" class="form-select" required>
                            <option value="">-- Cari Nama --</option>
                            @foreach($allPersons as $p)
                                <option value="{{ $p->id }}" 
                                    data-nama="{{ $p->nama }}" 
                                    data-id="{{ $p->type == 'users' ? $p->nisn : ($p->type == 'guru' ? $p->nip : $p->email) }}">
                                    {{ strtoupper($p->nama) }} ({{ strtoupper($p->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.7rem;">NAMA TERPILIH</label>
                            <input type="text" id="autoNama" name="nama" class="form-control input-premium bg-light" readonly style="font-size: 0.8rem;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.7rem;">NOMOR IDENTITAS</label>
                            <input type="text" id="autoID" name="npm" class="form-control input-premium bg-light" readonly style="font-size: 0.8rem;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.7rem;">WAKTU</label>
                            <input type="datetime-local" name="tanggal" class="form-control input-premium" value="{{ date('Y-m-d\TH:i') }}" required style="font-size: 0.8rem;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-2 fw-bold shadow-sm" style="font-size: 0.85rem;">SIMPAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#searchAnggota').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#createAbsenModal'),
        placeholder: "Cari nama..."
    });

    $('#searchAnggota').on('change', function() {
        var selected = $(this).find(':selected');
        $('#autoNama').val(selected.data('nama'));
        $('#autoID').val(selected.data('id'));
    });
});

$(document).on('click', '.btn-delete', function(e) {
    let form = $(this).closest('form');
    
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Data presensi yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', 
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true, 
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit(); 
        }
    })
});

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
@endif
</script>
@endsection