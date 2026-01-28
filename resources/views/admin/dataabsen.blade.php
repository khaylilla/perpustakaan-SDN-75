@extends('admin.layout')

@section('page-title', 'Presensi & Kehadiran Anggota')

@section('content')
<style>
    :root {
        --primary: #4e73df;
        --secondary: #858796;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --dark-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        --glass-white: rgba(255, 255, 255, 0.9);
        --surface: #ffffff;
    }

    .container-fluid {
        background: #f0f2f5;
        min-height: 100vh;
        padding: 30px;
    }

    /* Animated Header Section */
    .premium-header {
        background: var(--dark-gradient);
        border-radius: 24px;
        padding: 40px;
        color: white;
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(26, 26, 46, 0.15);
    }

    .premium-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(78, 115, 223, 0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    /* Floating Stats Cards */
    .stat-card {
        border: none;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: white;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 30px rgba(0,0,0,0.08);
    }

    .icon-shape {
        width: 55px;
        height: 55px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Search Bar Re-design */
    .filter-wrapper {
        background: var(--glass-white);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.03);
    }

    .input-premium {
        background: #f8f9fc;
        border: 1.5px solid #edf2f7;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .input-premium:focus {
        background: white;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    /* Luxury Table Design */
    .table-card {
        background: white;
        border-radius: 24px;
        border: none;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }

    .custom-table {
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .custom-table thead th {
        border: none;
        color: #a0aec0;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        padding: 15px 25px;
    }

    .custom-table tbody tr {
        background: white;
        transition: all 0.2s;
    }

    .custom-table tbody tr td {
        border: none;
        padding: 20px 25px;
        background: #fff;
    }

    .custom-table tbody tr td:first-child { border-radius: 15px 0 0 15px; }
    .custom-table tbody tr td:last-child { border-radius: 0 15px 15px 0; }

    .custom-table tbody tr:hover td {
        background: #f8faff;
        color: var(--primary);
    }

    /* Better Badge */
    .badge-time {
        background: #fff4e5;
        color: #d97706;
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Glowing Buttons */
    .btn-glow {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-weight: 600;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }

    .btn-glow:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 25px rgba(99, 102, 241, 0.4);
        color: white;
    }
</style>

<div class="container-fluid">

    {{-- HEADER SECTION --}}
    <div class="premium-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <h1 class="fw-bold mb-2">Absen Pengunjung</h1>
            <p class="mb-0 opacity-75">Sistem monitoring kunjungan perpustakaan cerdas & otomatis.</p>
        </div>
        <div class="mt-4 mt-md-0">
            <button class="btn btn-glow" data-bs-toggle="modal" data-bs-target="#createAbsenModal">
                <i class="bi bi-plus-lg me-2"></i> Input Manual
            </button>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="{{ route('admin.absen.scan') }}" class="text-decoration-none">
                <div class="card stat-card p-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-shape bg-soft-primary text-primary" style="background: #eef2ff;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-dark">Scanner QR</h6>
                            <small class="text-muted">Klik untuk mulai scan</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.kartu') }}" class="text-decoration-none">
                <div class="card stat-card p-2 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-shape bg-soft-info text-info" style="background: #e0f7fa;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold text-dark">Cetak Kartu</h6>
                            <small class="text-muted">Generate ID Anggota</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-2 shadow-sm border-start border-primary border-4">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-shape bg-primary text-white">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-dark">{{ $absens->count() }} Anggota</h6>
                        <small class="text-muted">Kunjungan Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="filter-wrapper">
        <form action="{{ route('admin.dataabsen') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Cari Nama/NIP</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-2" style="border-radius: 12px 0 0 12px;"><i class="bi bi-search"></i></span>
                    <input type="text" name="keyword" class="form-control input-premium border-start-0" placeholder="Ketik pencarian..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Kategori</label>
                <select name="type" class="form-select input-premium" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="siswa" {{ request('type') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('type') === 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-12">
                <label class="form-label small fw-bold text-uppercase text-muted">Periode Kunjungan</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control input-premium" value="{{ request('start_date') }}">
                    <span class="input-group-text bg-transparent border-0 px-2">-</span>
                    <input type="date" name="end_date" class="form-control input-premium" value="{{ request('end_date') }}">
                </div>
            </div>
            <div class="col-lg-3 col-md-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2">Filter</button>
                <a href="{{ route('admin.dataabsen.export', ['groupBy' => 'day']) }}" class="btn btn-outline-danger px-3 py-2 rounded-3">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="table-card shadow-sm">
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>NAMA ANGGOTA</th>
                        <th>IDENTITAS</th>
                        <th>WAKTU MASUK</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absens as $i => $absen)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-square me-3 bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold" 
                                     style="width: 45px; height: 45px; border-radius: 12px; background: #f0f4ff; font-size: 1.2rem;">
                                    {{ substr($absen->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $absen->nama }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Verified Member</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark px-3 py-2 border rounded-pill">{{ $absen->npm }}</span></td>
                        <td>
                            <span class="badge-time">
                                <i class="bi bi-clock-history me-2"></i>{{ \Carbon\Carbon::parse($absen->tanggal)->format('H:i | d M Y') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" style="border-radius: 12px;">
                                    <li><a class="dropdown-item rounded-3" href="#" data-bs-toggle="modal" data-bs-target="#editAbsenModal{{ $absen->id }}"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.dataabsen.delete', $absen->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="dropdown-item text-danger rounded-3" onclick="return confirm('Hapus data?')"><i class="bi bi-trash me-2"></i> Hapus</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/data-report.svg" style="width: 200px;" class="mb-3">
                            <h5 class="text-muted">Data Kunjungan Kosong</h5>
                            <p class="small text-muted">Belum ada aktivitas presensi hari ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL RE-DESIGN --}}
<div class="modal fade" id="createAbsenModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold">New Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.dataabsen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Anggota</label>
                        <select name="person_id" id="personSelect" class="form-select input-premium" required onchange="updatePersonData()">
                            <option value="">-- Cari Nama --</option>
                            @foreach($allPersons as $person)
                                <option value="{{ $person->id }}" data-nama="{{ $person->nama }}" 
                                    data-identifier="{{ $person->type === 'users' ? $person->nisn : ($person->type === 'guru' ? $person->nip : $person->email) }}">
                                    {{ $person->nama }} ({{ strtoupper($person->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" id="namaInput" class="form-control input-premium" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Identifier (NIP/NISN)</label>
                            <input type="text" name="npm" id="npmInput" class="form-control input-premium" readonly>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control input-premium" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light w-25" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold rounded-3">Simpan Absensi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script tetap sama namun pastikan ID elemen sesuai
    function updatePersonData() {
        const select = document.getElementById('personSelect');
        const selectedOption = select.options[select.selectedIndex];
        const namaInput = document.getElementById('namaInput');
        const npmInput = document.getElementById('npmInput');
        
        if (selectedOption.value) {
            namaInput.value = selectedOption.getAttribute('data-nama');
            npmInput.value = selectedOption.getAttribute('data-identifier');
        } else {
            namaInput.value = '';
            npmInput.value = '';
        }
    }
</script>
@endsection