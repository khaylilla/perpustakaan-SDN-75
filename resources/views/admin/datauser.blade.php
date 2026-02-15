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

    body { font-size: 0.875rem; }
    @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    @keyframes float { 0% { transform: translateY(0px) rotate(0deg); } 50% { transform: translateY(-15px) rotate(5deg); } 100% { transform: translateY(0px) rotate(0deg); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .fade-in-content { animation: fadeInUp 0.6s ease-out forwards; }

    .premium-header {
        position: relative;
        background: linear-gradient(-45deg, #1a1a2e, #16213e, #4e73df, #0a58ca);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        border-radius: 15px;
        padding: 25px 30px;
        color: white;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    /* FIX: Bubble Animation Added */
    .shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(5px);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        z-index: 1;
        animation: float 6s infinite ease-in-out;
    }
    .shape-1 { width: 100px; height: 100px; top: -20px; right: 5%; animation-delay: 0s; }
    .shape-2 { width: 70px; height: 70px; bottom: -15px; left: 10%; animation-delay: 2s; }
    
    .header-content { position: relative; z-index: 2; }
    .header-content h1 { font-size: 1.75rem; margin-bottom: 0.25rem; }
    .header-content p { font-size: 0.9rem; }

    .btn-action-add { 
        background: white; 
        color: var(--primary-blue); 
        padding: 10px 20px; 
        border-radius: 12px; 
        font-weight: 700; 
        font-size: 0.85rem;
        border: none; 
        transition: 0.3s; 
    }
    .btn-action-add:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1); color: var(--accent-red); }

    .floating-filter { 
        background: var(--glass-bg); 
        backdrop-filter: blur(15px); 
        border: 1px solid var(--glass-border); 
        border-radius: 18px; 
        padding: 15px 20px; 
        margin-top: -40px; 
        margin-bottom: 25px; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.08); 
        position: relative; z-index: 10;
    }

    .input-premium { font-size: 0.85rem; background: white; border: 2px solid #edf2f7; border-radius: 10px; padding: 8px 15px; }

    .table-glass-container { background: white; border-radius: 18px; padding: 10px; border: 1px solid #f1f5f9; }
    .table thead th { background: #f8fafc; font-size: 0.7rem; font-weight: 800; padding: 12px 15px; text-transform: uppercase; color: #64748b; }
    .table tbody td { padding: 10px 15px; font-size: 0.85rem; }
    .user-row:hover { background: #f8fafc; }

    .dot-badge { padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.7rem; }
    .dot-siswa { background: #e0e7ff; color: #4338ca; }
    .dot-guru { background: #fee2e2; color: var(--accent-red); }
    .dot-umum { background: #f1f5f9; color: #475569; }
    
    /* FIX: Action Button Colors */
    .btn-circle { width: 34px; height: 34px; border-radius: 10px; font-size: 0.9rem; display: inline-flex; align-items: center; justify-content: center; border: none; transition: 0.2s; }
    .btn-c-edit { background: #e0e7ff; color: var(--primary-blue); }
    .btn-c-delete { background: #fee2e2; color: var(--accent-red); }
    .btn-circle:hover { transform: scale(1.1); }
    
    .avatar-img { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; }
</style>

<div class="container-fluid py-3 fade-in-content">
    <div class="premium-header">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        
        <div class="header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold">Database Anggota</h1>
                    <p class="mb-0 opacity-80">SDN 75 Kota Bengkulu — Manajemen data digital efisien.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-action-add" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus-fill me-1"></i>Tambah Anggota
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER AREA --}}
    <div class="floating-filter">
        <form action="{{ route('admin.datauser') }}" method="GET" class="row g-2">
            <div class="col-md-7">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-primary"></i>
                    <input type="text" name="keyword" class="form-control input-premium ps-5" placeholder="Cari Nama, NISN, atau NIP..." value="{{ request('keyword') }}">
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
                <button type="submit" class="btn btn-primary w-100 h-100 rounded-3 fw-bold shadow-sm" style="font-size: 0.85rem;">Filter</button>
            </div>
        </form>
    </div>

    <div class="table-glass-container">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Profil</th>
                        <th>Kategori</th>
                        <th>ID / Identitas</th>
                        <th>Info Tambahan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr class="user-row">
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=0d6efd&color=fff' }}" class="avatar-img me-2">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $user->nama }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $user->email ?? $user->nisn ?? 'N/A' }}</div>
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
                            <code class="text-primary fw-bold" style="font-size: 0.75rem;">
                                {{ $user->type === 'users' ? 'NISN: '.($user->nisn ?? '-') : ($user->type === 'guru' ? 'NIP: '.($user->nip ?? '-') : 'Email Terdaftar') }}
                            </code>
                        </td>
                        <td>
                            <div style="font-size: 0.75rem;">
                                @if($user->type === 'users')
                                    <div class="text-truncate" style="max-width: 150px;"><i class="bi bi-house-door me-1"></i> {{ $user->asal_sekolah }}</div>
                                    <div class="text-primary fw-bold">Kelas: {{ $user->kelas }}</div>
                                @else
                                    <div><i class="bi bi-whatsapp me-1"></i> {{ $user->nohp }}</div>
                                    <div class="text-muted text-truncate" style="max-width: 150px;">{{ $user->alamat }}</div>
                                @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn-circle btn-c-edit" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->type }}-{{ $user->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('admin.datauser.delete', $user->id) }}?type={{ $user->type }}" method="POST" class="d-inline confirm-delete" data-name="{{ $user->nama }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-circle btn-c-delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h6 class="fw-bold mb-0">Tambah Anggota Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datauser.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Anggota</label>
                        <select id="categorySelectAdd" name="type" class="form-select input-premium" onchange="toggleAddFields()" required>
                            <option value="">-- Pilih --</option>
                            <option value="users">Siswa</option> 
                            <option value="guru">Guru</option>
                            <option value="umum">Umum</option>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control input-premium" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Password Login</label>
                            <input type="password" name="password" class="form-control input-premium" required>
                        </div>
                    </div>

                    <div id="fields_siswa_add" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">NISN</label><input type="text" name="nisn" class="form-control input-premium"></div>
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">Kelas</label><input type="text" name="kelas" class="form-control input-premium"></div>
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">Asal Sekolah</label><input type="text" name="asal_sekolah" class="form-control input-premium"></div>
                        </div>
                    </div>

                    <div id="fields_common_add" style="display:none;">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">Email</label><input type="email" name="email" class="form-control input-premium"></div>
                            <div class="col-md-6 mb-2" id="nip_wrap_add"><label class="form-label fw-bold">NIP</label><input type="text" name="nip" class="form-control input-premium"></div>
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">No HP / WA</label><input type="text" name="nohp" class="form-control input-premium"></div>
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">Tgl Lahir</label><input type="date" name="tgl_lahir" class="form-control input-premium"></div>
                            <div class="col-12 mb-2"><label class="form-label fw-bold">Alamat</label><textarea name="alamat" class="form-control input-premium" rows="2"></textarea></div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">Foto Profil</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-bold small">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
@foreach($users as $user)
<div class="modal fade" id="editUserModal-{{ $user->type }}-{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h6 class="fw-bold mb-0 text-truncate">Edit: {{ $user->nama }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.datauser.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="type" value="{{ $user->type }}">
                
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control input-premium" value="{{ $user->nama }}" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label fw-bold">Ganti Password</label>
                            <input type="password" name="password" class="form-control input-premium" placeholder="Isi jika ganti">
                        </div>
                    </div>

                    @if($user->type === 'users')
                        <div class="row g-2">
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">NISN</label><input type="text" name="nisn" class="form-control input-premium" value="{{ $user->nisn }}"></div>
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">Kelas</label><input type="text" name="kelas" class="form-control input-premium" value="{{ $user->kelas }}"></div>
                            <div class="col-md-4 mb-2"><label class="form-label fw-bold">Asal Sekolah</label><input type="text" name="asal_sekolah" class="form-control input-premium" value="{{ $user->asal_sekolah }}"></div>
                        </div>
                    @else
                        <div class="row g-2">
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">Email</label><input type="email" name="email" class="form-control input-premium" value="{{ $user->email }}"></div>
                            @if($user->type === 'guru')
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">NIP</label><input type="text" name="nip" class="form-control input-premium" value="{{ $user->nip }}"></div>
                            @endif
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">No HP</label><input type="text" name="nohp" class="form-control input-premium" value="{{ $user->nohp }}"></div>
                            <div class="col-md-6 mb-2"><label class="form-label fw-bold">Tgl Lahir</label><input type="date" name="tgl_lahir" class="form-control input-premium" value="{{ $user->tgl_lahir }}"></div>
                            <div class="col-12 mb-2"><label class="form-label fw-bold">Alamat</label><textarea name="alamat" class="form-control input-premium" rows="2">{{ $user->alamat }}</textarea></div>
                        </div>
                    @endif

                    <div class="mb-2">
                        <label class="form-label fw-bold">Update Foto</label>
                        <input type="file" name="foto" class="form-control input-premium">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-dark w-100 rounded-3 py-2 fw-bold small">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function toggleAddFields() {
    const category = document.getElementById('categorySelectAdd').value;
    const fieldsSiswa = document.getElementById('fields_siswa_add');
    const fieldsCommon = document.getElementById('fields_common_add');
    const nipWrap = document.getElementById('nip_wrap_add');

    fieldsSiswa.style.display = 'none';
    fieldsCommon.style.display = 'none';

    if (category === 'users') {
        fieldsSiswa.style.display = 'block';
    } else if (category === 'guru') {
        fieldsCommon.style.display = 'block';
        nipWrap.style.display = 'block';
    } else if (category === 'umum') {
        fieldsCommon.style.display = 'block';
        nipWrap.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('form.confirm-delete').forEach(function(form){
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const name = form.dataset.name || 'data ini';
            Swal.fire({
                title: 'Hapus data?',
                text: `Hapus ${name} secara permanen?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });
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