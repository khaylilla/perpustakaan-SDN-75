@extends('admin.layout')

@section('page-title', 'Database Anggota')

@section('content')
<style>
    :root {
        --primary-blue: #0d6efd;
        --deep-blue: #0a58ca;
        --accent-red: #dc3545;
        --soft-white: #f8fafc;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.5);
    }

    /* Keyframes for Animated Gradient */
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Main Page Wrapper */
    .fade-in-content {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    /* MODERN ANIMATED HEADER */
    .premium-header {
        position: relative;
        background: linear-gradient(-45deg, #0d6efd, #0a58ca, #dc3545, #9b1c2e);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        border-radius: 30px;
        padding: 30px 40px;
        color: white;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.25);
        margin-bottom: 60px;
    }

    /* Decorative Floating Shapes */
    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        animation: float 8s infinite ease-in-out;
        z-index: 1;
    }
    .shape-1 { width: 150px; height: 150px; top: -20px; right: 10%; animation-delay: 0s; }
    .shape-2 { width: 100px; height: 100px; bottom: -10px; left: 5%; animation-delay: 2s; }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .btn-action-add {
        background: white;
        color: var(--primary-blue);
        padding: 14px 32px;
        border-radius: 16px;
        font-weight: 700;
        border: none;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .btn-action-add:hover {
        transform: scale(1.05) translateY(-5px);
        background: #f8fafc;
        color: var(--accent-red);
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }

    /* GLASSMORPHISM FILTER */
    .floating-filter {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        margin-top: -80px;
        margin-bottom: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        z-index: 10;
        position: relative;
    }

    .input-premium {
        background: white;
        border: 2px solid #edf2f7;
        border-radius: 14px;
        padding: 12px 20px;
        transition: 0.3s;
    }

    .input-premium:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    /* TABLE DESIGN */
    .table-glass-container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        padding: 20px;
        border: 1px solid #f1f5f9;
    }

    .table thead th {
        background: #f8fafc;
        border: none;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 20px;
    }

    .user-row {
        transition: 0.3s;
        border-bottom: 1px solid #f8fafc;
    }

    .user-row:hover {
        background: #f1f5f9;
        transform: translateX(5px);
    }

    /* BADGES & BUTTONS */
    .dot-badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.75rem;
    }
    .dot-siswa { background: #e0e7ff; color: #4338ca; }
    .dot-guru { background: #fee2e2; color: var(--accent-red); }
    .dot-umum { background: #f1f5f9; color: #475569; }

    .btn-circle {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        border: none;
    }
    .btn-c-view { background: #f1f5f9; color: #64748b; }
    .btn-c-edit { background: #e0e7ff; color: var(--primary-blue); }
    .btn-c-delete { background: #fee2e2; color: var(--accent-red); }
    
    .btn-circle:hover { 
        transform: scale(1.15);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>

<div class="container-fluid py-4 fade-in-content">

    {{-- DYNAMIC ANIMATED HEADER --}}
    <div class="premium-header">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-2">Database Anggota</h1>
                    <p class="lead mb-0 opacity-80">SDN 75 Kota Bengkulu — Manajemen data digital yang modern dan efisien.</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <button class="btn btn-action-add" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- GLASS FILTER AREA --}}
    <div class="floating-filter">
        <form action="{{ route('admin.datauser') }}" method="GET" class="row g-3">
            <div class="col-md-7">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-primary" style="font-size: 1.2rem;"></i>
                    <input type="text" name="keyword" class="form-control input-premium ps-5" placeholder="Cari berdasarkan Nama, NISN, atau NIP..." value="{{ request('keyword') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select input-premium" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="users" {{ request('category') === 'users' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ request('category') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="umum" {{ request('category') === 'umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 h-100 rounded-4 fw-bold shadow-sm">
                    <i class="bi bi-filter me-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-lg rounded-4 py-3 mb-4 animate__animated animate__bounceInRight">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                <div>
                    <span class="fw-bold">Berhasil!</span> {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    {{-- MAIN DATA TABLE --}}
    <div class="table-glass-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Profil Anggota</th>
                        <th>Kategori</th>
                        <th>Identitas Resmi</th>
                        <th>Detail Kontak</th>
                        <th class="text-end pe-4">Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $categoryFilter = request('category');
                        $filteredUsers = $categoryFilter ? $users->filter(fn($u) => $u->type === $categoryFilter) : $users;
                    @endphp

                    @forelse($filteredUsers as $index => $user)
                    <tr class="user-row">
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="{{ $user->foto ? asset('storage/foto/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=0d6efd&color=fff' }}" 
                                         class="rounded-4 me-3" style="width: 52px; height: 52px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0 fs-6">{{ $user->nama }}</div>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($user->alamat ?? 'Lokasi tidak ada', 25) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->type === 'users')
                                <span class="dot-badge dot-siswa"><i class="bi bi-person me-1"></i> Siswa</span>
                            @elseif($user->type === 'guru')
                                <span class="dot-badge dot-guru"><i class="bi bi-briefcase me-1"></i> Guru</span>
                            @else
                                <span class="dot-badge dot-umum"><i class="bi bi-people me-1"></i> Umum</span>
                            @endif
                        </td>
                        <td>
                            <code class="text-primary fw-bold" style="background: #f1f5f9; padding: 4px 10px; border-radius: 8px;">
                                @if($user->type === 'users') {{ $user->nisn ?? 'N/A' }}
                                @elseif($user->type === 'guru') {{ $user->nip ?? 'N/A' }}
                                @else {{ $user->email ?? 'N/A' }}
                                @endif
                            </code>
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark fw-medium mb-1"><i class="bi bi-tag-fill me-2 text-primary"></i>{{ $user->type === 'users' ? 'Kelas '.$user->kelas : 'Staf Administrasi' }}</div>
                                <div class="text-muted"><i class="bi bi-whatsapp me-2"></i>{{ $user->nohp ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="btn-circle btn-c-view" title="Lihat Profil"><i class="bi bi-eye-fill"></i></a>
                                <button class="btn-circle btn-c-edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->type }}-{{ $user->id }}" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.datauser.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-circle btn-c-delete" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/blue/abstract-art-4.svg" style="width: 200px;" class="mb-4">
                            <h5 class="text-muted fw-bold">Belum ada anggota yang terdaftar</h5>
                            <p class="text-muted">Klik tombol "Tambah Anggota" untuk memulai input data.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODALS (MODAL CODE REMAINS THE SAME FOR FUNCTIONALITY) --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <h5 class="fw-bold mb-0">Tambah Anggota Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datauser.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Anggota</label>
                        <select id="categorySelect" class="form-select input-premium" onchange="toggleFields()" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control input-premium" required placeholder="Contoh: Budi Santoso">
                    </div>
                    <div id="siswaFields" style="display:none;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn" class="form-control input-premium">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" class="form-control input-premium">
                            </div>
                        </div>
                    </div>
                    <div id="guruFields" style="display:none;">
                        <div class="mb-3"><label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control input-premium">
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Nomor WhatsApp</label>
                        <input type="text" name="nohp" class="form-control input-premium">
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Unggah Foto</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->type }}-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white border-0 p-4">
                <h5 class="fw-bold mb-0">Perbarui Data Anggota</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datauser.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control input-premium" value="{{ $user->nama }}" required>
                    </div>
                    @if($user->type === 'users')
                        <div class="mb-3"><label class="form-label">NISN</label>
                            <input type="text" name="nisn" class="form-control input-premium" value="{{ $user->nisn ?? '' }}">
                        </div>
                    @endif
                    <div class="mb-3"><label class="form-label fw-bold">Nomor WhatsApp</label>
                        <input type="text" name="nohp" class="form-control input-premium" value="{{ $user->nohp ?? '' }}">
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Ganti Foto Profil</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function toggleFields() {
    const category = document.getElementById('categorySelect').value;
    document.getElementById('siswaFields').style.display = category === 'siswa' ? 'block' : 'none';
    document.getElementById('guruFields').style.display = category === 'guru' ? 'block' : 'none';
}
</script>

@endsection