@extends('admin.layout')

@section('page-title', 'Presensi & Kehadiran Anggota')

@section('content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --orange-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
    }

    .container-fluid {
        padding: 20px;
        background-color: #f8f9fa;
    }

    /* Modern Page Header */
    .page-header-premium {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(118, 75, 162, 0.2);
        position: relative;
        overflow: hidden;
    }

    .page-header-premium::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* Card Styling */
    .premium-card {
        border: none;
        border-radius: 18px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }

    .icon-box {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Table Styling */
    .table-container-premium {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .table thead th {
        background-color: #fcfaff;
        border: none;
        color: #764ba2;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        padding: 20px;
    }

    .table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f1f1f1;
        font-size: 0.9rem;
    }

    /* Search & Filter Bar */
    .search-filter-section {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .form-premium {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        transition: 0.3s;
    }

    .form-premium:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Buttons */
    .btn-premium-add {
        background: var(--accent-gradient);
        border: none;
        color: white;
        border-radius: 12px;
        padding: 12px 25px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-premium-primary {
        background: var(--primary-gradient);
        border: none;
        color: white;
        border-radius: 10px;
        padding: 8px 20px;
    }

    /* Status Badges */
    .badge-date {
        background: #eef2ff;
        color: #4338ca;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block;
    }

    .action-icon-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin: 0 3px;
        transition: 0.2s;
        border: none;
    }

    .edit-btn { background: #fff7ed; color: #f97316; }
    .edit-btn:hover { background: #f97316; color: white; }
    .delete-btn { background: #fef2f2; color: #ef4444; }
    .delete-btn:hover { background: #ef4444; color: white; }

    @media print {
        .page-header-premium, .search-filter-section, .btn-premium-add { display: none !important; }
    }
</style>

<div class="container-fluid">

    {{-- MODERN HEADER --}}
    <div class="page-header-premium d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-1">Manajemen Presensi</h2>
            <p class="mb-0 opacity-75">Pantau dan kelola kehadiran anggota perpustakaan secara real-time</p>
        </div>
        <button class="btn btn-premium-add shadow-lg" data-bs-toggle="modal" data-bs-target="#createAbsenModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Absensi Manual
        </button>
    </div>

    {{-- QUICK NAVIGATION CARDS (TANPA KOTAK DATA USER) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="{{ route('admin.absen.scan') }}" class="text-decoration-none">
                <div class="card premium-card shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="icon-box me-3"><i class="bi bi-qr-code-scan fs-4"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Scanner QR</h6>
                            <small class="opacity-75">Absen Cepat Otomatis</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.kartu') }}" class="text-decoration-none">
                <div class="card premium-card shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="icon-box me-3"><i class="bi bi-credit-card fs-4"></i></div>
                        <div>
                            <h6 class="mb-0 fw-bold">Cetak Kartu</h6>
                            <small class="opacity-75">ID Card Anggota</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card premium-card shadow-sm h-100" style="background: white; border: 1px solid #eee;">
                <div class="card-body d-flex align-items-center p-4">
                    <div class="icon-box me-3 bg-light text-primary"><i class="bi bi-info-circle fs-4"></i></div>
                    <div>
                        <h6 class="mb-0 text-dark fw-bold">{{ $absens->count() }} Kunjungan</h6>
                        <small class="text-muted">Total Kehadiran Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH & FILTER SECTION (INPUT PENCARIAN DIPERKECIL) --}}
    <div class="search-filter-section shadow-sm">
        <form action="{{ route('admin.dataabsen') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-2"><i class="bi bi-search me-1"></i> CARI DATA</label>
                <input type="text" name="keyword" class="form-control form-premium" placeholder="NISN/NIP..." value="{{ request('keyword') }}">
            </div>
            
            <div class="col-md-2">
                <label class="small fw-bold text-muted mb-2">TIPE</label>
                <select name="type" class="form-select form-premium" onchange="this.form.submit()">
                    <option value="">Semua</option>
                    <option value="siswa" {{ request('type') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('type') === 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
            
            <div class="col-md-5">
                <label class="small fw-bold text-muted mb-2">RENTANG TANGGAL</label>
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control form-premium" value="{{ request('start_date') }}">
                    <span class="input-group-text bg-transparent border-0 fw-bold">s/d</span>
                    <input type="date" name="end_date" class="form-control form-premium" value="{{ request('end_date') }}">
                </div>
            </div>
            
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-premium-primary w-100 fw-bold shadow-sm">Terapkan Filter</button>
                <a href="{{ route('admin.dataabsen.export', ['groupBy' => 'day']) }}" class="btn btn-outline-danger border-2 rounded-3" title="Export PDF">
                    <i class="bi bi-file-pdf"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- TABLE DATA --}}
    <div class="table-container-premium border-0">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Identitas Anggota</th>
                        <th>ID Anggota</th>
                        <th>Waktu Kunjungan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absens as $i => $absen)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-soft-primary text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 10px; background: #f0f4ff;">
                                        {{ substr($absen->nama, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $absen->nama }}</div>
                                </div>
                            </td>
                            <td><span class="text-muted small fw-bold">{{ $absen->npm }}</span></td>
                            <td><span class="badge-date"><i class="bi bi-clock me-2"></i>{{ $absen->tanggal }}</span></td>
                            <td class="text-center">
                                <button class="action-icon-btn edit-btn" data-bs-toggle="modal" data-bs-target="#editAbsenModal{{ $absen->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.dataabsen.delete', $absen->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-icon-btn delete-btn" onclick="return confirm('Hapus data ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/empty-states.svg" style="width: 150px;" class="mb-3 opacity-50">
                                <p class="text-muted">Tidak ada data absensi yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH ABSEN --}}
<div class="modal fade" id="createAbsenModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Tambah Absensi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.dataabsen.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">PILIH DARI DATABASE</label>
                        <select name="person_id" id="personSelect" class="form-select form-premium" required onchange="updatePersonData()">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($allPersons as $person)
                                <option value="{{ $person->id }}" data-nama="{{ $person->nama }}" 
                                    data-identifier="{{ $person->type === 'users' ? $person->nisn : ($person->type === 'guru' ? $person->nip : $person->email) }}">
                                    {{ $person->nama }} ({{ $person->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NAMA LENGKAP</label>
                        <input type="text" name="nama" id="namaInput" class="form-control form-premium" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">ID (NISN/NIP/EMAIL)</label>
                        <input type="text" name="npm" id="npmInput" class="form-control form-premium" required>
                    </div>
                    <div class="mb-0">
                        <label class="small fw-bold text-muted mb-1">TANGGAL ABSEN</label>
                        <input type="date" name="tanggal" class="form-control form-premium" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-premium-primary fw-bold shadow">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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