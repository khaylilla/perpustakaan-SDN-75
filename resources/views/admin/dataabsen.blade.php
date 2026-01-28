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

    /* Animasi Background Header */
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
        border-radius: 25px;
        padding: 50px 40px;
        color: white;
        overflow: hidden;
        margin-bottom: 50px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    /* Floating Shapes (Elemen yang sempat hilang) */
    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(5px);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        z-index: 1;
    }
    .shape-1 { width: 150px; height: 150px; top: -30px; right: 5%; }
    .shape-2 { width: 100px; height: 100px; bottom: -20px; left: 10%; }

    /* Stats Card Overlap */
    .stats-container {
        margin-top: -85px;
        position: relative;
        z-index: 10;
        padding: 0 15px;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-10px); }

    /* Filter & Table Container */
    .glass-section {
        background: white;
        border-radius: 24px;
        padding: 30px;
        margin-top: 30px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
    }

    .input-premium {
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px 15px;
        transition: 0.3s;
    }
    .input-premium:focus {
        border-color: var(--primary-blue);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    .badge-time {
        background: #f0f4ff;
        color: #4e73df;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid py-4" style="background: #f8fafc; min-height: 100vh;">

    <div class="premium-header">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div style="position: relative; z-index: 2;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-6 fw-bold mb-2 text-white">Log Presensi Kehadiran</h1>
                    <p class="lead mb-0 opacity-75">SDN 75 Kota Bengkulu — Monitoring Perpustakaan Digital</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <button class="btn btn-white btn-lg fw-bold px-4 rounded-3 shadow" 
                            style="background: white; color: var(--deep-blue); border: none;"
                            data-bs-toggle="modal" data-bs-target="#createAbsenModal">
                        <i class="bi bi-plus-circle-fill me-2"></i>Absen Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-container row g-4">
        <div class="col-md-4">
            <a href="{{ route('admin.absen.scan') }}" class="text-decoration-none">
                <div class="card stat-card p-2 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-4 bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-qr-code-scan fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-dark">Scanner QR</h6>
                            <small class="text-muted">Gunakan Kamera</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-2 border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-4 bg-success text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-dark">{{ $absens->count() }} Anggota</h6>
                        <small class="text-muted">Kunjungan Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.kartu') }}" class="text-decoration-none">
                <div class="card stat-card p-2 border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="rounded-4 bg-info text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-dark">Cetak Kartu</h6>
                            <small class="text-muted">Data Anggota</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="glass-section">
        <form action="{{ route('admin.dataabsen') }}" method="GET" class="row g-3 mb-5 align-items-end">
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-2">CARI NAMA</label>
                <input type="text" name="keyword" class="form-control input-premium" placeholder="Nama / NIP / NISN..." value="{{ request('keyword') }}">
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-2">KATEGORI</label>
                <select name="type" class="form-select input-premium">
                    <option value="">Semua</option>
                    <option value="siswa" {{ request('type') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('type') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-2">RENTANG TANGGAL</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control input-premium" value="{{ request('start_date') }}">
                    <span class="input-group-text bg-transparent border-0 px-2">-</span>
                    <input type="date" name="end_date" class="form-control input-premium" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold shadow-sm">Filter Data</button>
                <a href="{{ route('admin.dataabsen.export', ['groupBy' => 'day']) }}" class="btn btn-outline-danger px-3 rounded-3 shadow-sm">
                    <i class="bi bi-file-pdf"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-muted" style="font-size: 0.75rem; border-bottom: 2px solid #f1f5f9;">
                        <th class="pb-3">NAMA ANGGOTA</th>
                        <th class="pb-3">IDENTITAS</th>
                        <th class="pb-3">WAKTU MASUK</th>
                        <th class="pb-3 text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absens as $absen)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary fw-bold rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    {{ substr($absen->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $absen->nama }}</div>
                                    <small class="text-muted"><i class="bi bi-check-circle-fill text-success me-1"></i>Hadir</small>
                                </div>
                            </div>
                        </td>
                        <td><code class="px-2 py-1 bg-light rounded text-dark border">{{ $absen->npm }}</code></td>
                        <td><span class="badge-time">{{ \Carbon\Carbon::parse($absen->tanggal)->format('H:i') }} WIB</span></td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-3">
                                    <li>
                                        <form action="{{ route('admin.dataabsen.delete', $absen->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger rounded-2" onclick="return confirm('Hapus data?')">
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <h6 class="text-muted">Tidak ada data kehadiran ditemukan.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createAbsenModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <h5 class="fw-bold mb-0">Input Presensi Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.dataabsen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">CARI & PILIH ANGGOTA</label>
                        <select name="person_id" id="searchAnggota" class="form-select" required>
                            <option value="">-- Ketik Nama Siswa/Guru --</option>
                            @foreach($allPersons as $p)
                                <option value="{{ $p->id }}" 
                                    data-nama="{{ $p->nama }}" 
                                    data-id="{{ $p->type == 'users' ? $p->nisn : ($p->type == 'guru' ? $p->nip : $p->email) }}">
                                    {{ strtoupper($p->nama) }} ({{ strtoupper($p->type) }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-primary mt-1 d-block">Admin bisa mengetik nama untuk memfilter list.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">NAMA TERPILIH</label>
                            <input type="text" id="autoNama" name="nama" class="form-control input-premium bg-light" readonly placeholder="Pilih anggota di atas...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">NOMOR IDENTITAS (NISN/NIP)</label>
                            <input type="text" id="autoID" name="npm" class="form-control input-premium bg-light" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">TANGGAL & WAKTU</label>
                            <input type="datetime-local" name="tanggal" class="form-control input-premium" value="{{ date('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow">SIMPAN KEHADIRAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Aktifkan Fitur Cari (Select2)
    $('#searchAnggota').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#createAbsenModal'),
        placeholder: "Ketik nama untuk mencari..."
    });

    // Logic mengisi input otomatis berdasarkan pilihan dropdown
    $('#searchAnggota').on('change', function() {
        var selected = $(this).find(':selected');
        $('#autoNama').val(selected.data('nama'));
        $('#autoID').val(selected.data('id'));
    });
});
</script>

@endsection