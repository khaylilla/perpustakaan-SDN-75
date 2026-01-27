@extends('admin.layout')

@section('page-title', 'Database Anggota')

@section('content')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --accent-orange: #f7931e;
    }

    /* Background Decoration */
    .bg-glow {
        position: fixed;
        top: 0; right: 0; width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
        z-index: -1;
    }

    /* Header Styling */
    .premium-header {
        background: var(--primary-gradient);
        border-radius: 24px;
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
        margin-bottom: 40px;
    }

    .premium-header::after {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .btn-glass-add {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 28px;
        border-radius: 14px;
        font-weight: 600;
        transition: 0.4s;
    }

    .btn-glass-add:hover {
        background: white;
        color: #6366f1;
        transform: translateY(-3px);
    }

    /* Search & Filter - Floating Card */
    .floating-filter {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 25px;
        margin-top: -60px;
        margin-bottom: 35px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        z-index: 10;
        position: relative;
    }

    .input-premium {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        transition: 0.3s;
    }

    .input-premium:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    /* Card Table Styling */
    .table-glass-container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.03);
        padding: 10px;
    }

    .table thead th {
        background: none;
        border-bottom: 2px solid #f1f5f9;
        color: #94a3b8;
        font-size: 0.8rem;
        padding: 20px;
    }

    .user-row {
        transition: 0.3s;
        border-radius: 15px;
    }

    .user-row:hover {
        background: #f8fafc;
        transform: scale(1.005);
    }

    /* Minimalist Badges */
    .dot-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .dot-siswa { background: #eef2ff; color: #4f46e5; }
    .dot-guru { background: #f0fdf4; color: #16a34a; }
    .dot-umum { background: #fff7ed; color: #ea580c; }

    /* Action Circle Buttons */
    .btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        margin: 0 3px;
        transition: 0.3s;
    }
    .btn-c-view { background: #f1f5f9; color: #64748b; }
    .btn-c-edit { background: #fef9c3; color: #a16207; }
    .btn-c-delete { background: #fee2e2; color: #dc2626; }
    
    .btn-circle:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

</style>

<div class="bg-glow"></div>

<div class="container-fluid py-3">

    {{-- PREMIUM HEADER --}}
    <div class="premium-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-white bg-opacity-25 mb-3 px-3 py-2 rounded-pill">Admin Dashboard v2.0</span>
                <h1 class="fw-bold mb-1">Database Anggota</h1>
                <p class="mb-0 opacity-75">Kelola informasi anggota perpustakaan dengan antarmuka cerdas.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-glass-add" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Baru
                </button>
            </div>
        </div>
    </div>

    {{-- FLOATING FILTER --}}
    <div class="floating-filter">
        <form action="{{ route('admin.datauser') }}" method="GET" class="row g-3">
            <div class="col-md-7">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" name="keyword" class="form-control input-premium ps-5" placeholder="Cari nama, NISN, atau NIP anggota..." value="{{ request('keyword') }}">
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
                <button type="submit" class="btn btn-dark w-100 h-100 rounded-3 fw-bold">Terapkan</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 py-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- DATA TABLE --}}
    <div class="table-glass-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Identitas Anggota</th>
                        <th>Kategori</th>
                        <th>Kode Identitas</th>
                        <th>Keterangan</th>
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
                                    <img src="{{ $user->foto ? asset('storage/foto/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=6366f1&color=fff' }}" 
                                         class="rounded-4 me-3" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <span class="position-absolute bottom-0 end-0 translate-middle-x p-1 bg-success border border-white border-2 rounded-circle"></span>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $user->nama }}</div>
                                    <small class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i>{{ Str::limit($user->alamat ?? 'Belum ada alamat', 20) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->type === 'users')
                                <span class="dot-badge dot-siswa">Siswa</span>
                            @elseif($user->type === 'guru')
                                <span class="dot-badge dot-guru">Guru</span>
                            @else
                                <span class="dot-badge dot-umum">Umum</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                @if($user->type === 'users') {{ $user->nisn ?? '-' }}
                                @elseif($user->type === 'guru') {{ $user->nip ?? '-' }}
                                @else {{ $user->email ?? '-' }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="small">
                                <span class="d-block"><i class="bi bi-mortarboard me-1"></i> {{ $user->type === 'users' ? 'Kelas '.$user->kelas : 'Staff/Umum' }}</span>
                                <span class="text-muted"><i class="bi bi-phone me-1"></i> {{ $user->nohp ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <a href="#" class="btn-circle btn-c-view" title="Lihat Profil"><i class="bi bi-eye"></i></a>
                            <button class="btn-circle btn-c-edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->type }}-{{ $user->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.datauser.delete', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-circle btn-c-delete" onclick="return confirm('Hapus data ini secara permanen?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <img src="https://illustrations.popsy.co/gray/box.svg" style="width: 150px;" class="mb-3 opacity-50">
                            <h6 class="text-muted fw-bold">Belum ada data anggota yang tersimpan.</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- KODE MODAL TETAP DIBAWAH --}}
{{-- MODAL TAMBAH USER --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datauser.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Kategori</label>
                        <select id="categorySelect" class="form-select input-premium" onchange="toggleFields()" required>
                            <option value="">-- Kategori --</option>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control input-premium" required placeholder="Masukkan nama...">
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
                        <div class="mb-3">
                            <label class="form-label">Asal Sekolah</label>
                            <input type="text" name="asal_sekolah" class="form-control input-premium">
                        </div>
                    </div>

                    <div id="guruFields" style="display:none;">
                        <div class="mb-3"><label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control input-premium">
                        </div>
                        <div class="mb-3"><label class="form-label">Email</label>
                            <input type="email" name="email_guru" class="form-control input-premium">
                        </div>
                    </div>

                    <div id="umumFields" style="display:none;">
                        <div class="mb-3"><label class="form-label">Email</label>
                            <input type="email" name="email_umum" class="form-control input-premium">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label fw-bold">No HP</label>
                            <input type="text" name="nohp" class="form-control input-premium">
                        </div>
                        <div class="col-md-6 mb-3"><label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control input-premium" required>
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label fw-bold">Foto</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->type }}-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Data Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                    @elseif($user->type === 'umum' || $user->type === 'guru')
                        <div class="mb-3"><label class="form-label">Email/NIP</label>
                            <input type="text" name="identitas" class="form-control input-premium" value="{{ $user->nip ?? $user->email }}">
                        </div>
                    @endif
                    
                    <div class="mb-3"><label class="form-label fw-bold">No HP</label>
                        <input type="text" name="nohp" class="form-control input-premium" value="{{ $user->nohp ?? '' }}">
                    </div>
                    <div class="mb-3"><label class="form-label">Foto Baru</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
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
    document.getElementById('umumFields').style.display = category === 'umum' ? 'block' : 'none';
}
</script>

@endsection