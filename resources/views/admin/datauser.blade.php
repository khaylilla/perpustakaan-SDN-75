@extends('admin.layout')

@section('page-title', 'Manajemen Data User')

@section('content')
<style>
  .page-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
  }

  .page-title h4 {
    font-weight: 700;
    color: #2e2e2e;
  }

  .btn-add {
    background-color: #4a4ca4;
    color: #fff;
    border-radius: 8px;
    font-weight: 600;
    padding: 10px 16px;
  }

  .btn-add:hover {
    background-color: #3c3f91;
    color: #fff;
  }

  .search-bar {
  background: white;
  border-radius: 12px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  padding: 10px 15px;
  margin-bottom: 20px;
  width: 100%;
  max-width: 80%;  /* agar penuh selebar container */
}

 .search-bar input {
  border: none;
  outline: none;
  flex: 1;
  font-size: 15px;
  padding-left: 8px;
}

.search-bar .btn {
  white-space: nowrap;
}

  .table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    padding: 0;
    overflow-x: auto;
  }

  table {
    margin-bottom: 0;
    min-width: 900px;
  }

  thead {
    background-color: #e7e8fc;
    color: #333;
  }

  th, td {
    vertical-align: middle !important;
    white-space: nowrap;
  }

  .table tbody tr:hover {
    background-color: #f6f6ff;
  }

  .action-icons i {
    font-size: 18px;
    cursor: pointer;
    margin: 0 6px;
  }

  .action-icons .view { color: #0066ff; }
  .action-icons .edit { color: #f39c12; }
  .action-icons .delete { color: #e74c3c; }

  .rounded-circle {
    object-fit: cover;
  }
</style>

<div class="container-fluid">

    {{-- HEADER BARU --}}
  <div class="d-flex flex-wrap gap-3 mb-4">

    <a href="{{ route('admin.datauser') }}" 
       class="text-decoration-none flex-grow-1"
       style="max-width: 300px;">
      <div class="card shadow-sm border-0 text-white"
           style="background: linear-gradient(135deg, #f7931e, #ffa94d); border-radius: 16px;">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="fw-bold mb-1">Manajemen Data User</h5>
            <p class="mb-0 text-light small">Kelola anggota perpustakaan</p>
          </div>
          <i class="bi bi-people-fill fs-2 opacity-75"></i>
        </div>
      </div>
    </a>

    <a href="{{ route('admin.dataabsen') }}" 
       class="text-decoration-none flex-grow-1"
       style="max-width: 300px;">
      <div class="card shadow-sm border-0 text-white"
           style="background: linear-gradient(135deg, #f7931e, #ffb84d); border-radius: 16px;">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="fw-bold mb-1">Manajemen Data Absen</h5>
            <p class="mb-0 text-light small">Pantau kehadiran anggota</p>
          </div>
          <i class="bi bi-calendar-check-fill fs-2 opacity-75"></i>
        </div>
      </div>
    </a>

    {{-- BUTTON TAMBAH ANGGOTA --}}
  <div class="d-flex align-items-center ms-auto mb-3">
    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createUserModal">
      + Tambah Anggota
    </button>
  </div>

  {{-- SEARCH BAR (posisi di bawah tombol) --}}
  <form action="{{ route('admin.datauser') }}" method="GET" class="d-flex gap-2 mb-3">
    <div class="search-bar flex-grow-1">
      <i class="bi bi-search"></i>
      <input type="text" name="keyword" id="searchInput" placeholder="Cari nama atau identitas..." value="{{ request('keyword') }}">
      <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </div>
    
    <div style="width: 220px;">
      <select name="category" id="categoryFilter" class="form-control" onchange="this.form.submit()">
        <option value="">-- Semua Kategori --</option>
        <option value="users" {{ request('category') === 'users' ? 'selected' : '' }}>Siswa</option>
        <option value="umum" {{ request('category') === 'umum' ? 'selected' : '' }}>Pengunjung Umum</option>
        <option value="guru" {{ request('category') === 'guru' ? 'selected' : '' }}>Guru</option>
      </select>
    </div>
  </form>

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- FILTER DATA --}}
  @php
    $categoryFilter = request('category');
    $filteredUsers = $categoryFilter ? $users->filter(fn($u) => $u->type === $categoryFilter) : $users;
  @endphp

  {{-- TABEL GABUNGAN --}}
  <div class="table-container">
    <table class="table text-center align-middle mb-0">
      <thead>
        <tr style="background-color: #4a4ca4; color: white;">
          <th>No</th>
          <th>Kategori</th>
          <th>Nama</th>
          <th>Identitas (NISN/NIP/Email)</th>
          <th>Alamat</th>
          <th>Tgl Lahir</th>
          <th>No HP</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($filteredUsers as $index => $user)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              @if($user->type === 'users')
                <span class="badge" style="background-color: #4a4ca4;">Siswa</span>
              @elseif($user->type === 'guru')
                <span class="badge" style="background-color: #27ae60;">Guru</span>
              @else
                <span class="badge" style="background-color: #f39c12;">Umum</span>
              @endif
            </td>
            <td>
              <div class="d-flex align-items-center justify-content-center">
                @if(!empty($user->foto))
                  <img src="{{ asset('storage/foto/'.$user->foto) }}" 
                      alt=" " 
                      class="rounded-circle me-2" 
                      style="width:40px; height:40px;">
                @endif
                <span>{{ $user->nama }}</span>
              </div>
            </td>
            <td>
              @if($user->type === 'users')
                {{ $user->nisn ?? '-' }}
              @elseif($user->type === 'guru')
                {{ $user->nip ?? '-' }}
              @else
                {{ $user->email ?? '-' }}
              @endif
            </td>
            <td>{{ $user->alamat ?? '-' }}</td>
            <td>{{ $user->tgl_lahir ? \Carbon\Carbon::parse($user->tgl_lahir)->format('d-m-Y') : '-' }}</td>
            <td>{{ $user->nohp ?? '-' }}</td>
            <td class="action-icons">
              <i class="bi bi-eye view" title="Lihat"></i>
              <i class="bi bi-pencil-square edit" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->type }}-{{ $user->id }}" title="Edit"></i>
              <form action="{{ route('admin.datauser.delete', $user->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="return confirm('Yakin hapus data ini?')">
                  <i class="bi bi-trash delete" title="Hapus"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-muted">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- MODAL EDIT USERS --}}
  @foreach($users as $user)
    <div class="modal fade" id="editUserModal{{ $user->type }}-{{ $user->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title">Edit Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <form action="{{ route('admin.datauser.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class="mb-3"><label>Nama</label>
                <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" required>
              </div>
              
              @if($user->type === 'users')
                <div class="mb-3"><label>NISN</label>
                  <input type="text" name="nisn" class="form-control" value="{{ $user->nisn ?? '' }}" required>
                </div>
                <div class="mb-3"><label>Asal Sekolah</label>
                  <input type="text" name="asal_sekolah" class="form-control" value="{{ $user->asal_sekolah ?? '' }}">
                </div>
                <div class="mb-3"><label>Kelas</label>
                  <input type="text" name="kelas" class="form-control" value="{{ $user->kelas ?? '' }}">
                </div>
              @elseif($user->type === 'umum')
                <div class="mb-3"><label>Email</label>
                  <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" required>
                </div>
                <div class="mb-3"><label>Alamat</label>
                  <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
                </div>
                <div class="mb-3"><label>Tanggal Lahir</label>
                  <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
                </div>
              @elseif($user->type === 'guru')
                <div class="mb-3"><label>NIP</label>
                  <input type="text" name="nip" class="form-control" value="{{ $user->nip ?? '' }}" required>
                </div>
                <div class="mb-3"><label>Email</label>
                  <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}">
                </div>
                <div class="mb-3"><label>Alamat</label>
                  <input type="text" name="alamat" class="form-control" value="{{ $user->alamat ?? '' }}">
                </div>
                <div class="mb-3"><label>Tanggal Lahir</label>
                  <input type="date" name="tgl_lahir" class="form-control" value="{{ $user->tgl_lahir ?? '' }}">
                </div>
              @endif
              
              <div class="mb-3"><label>Nomor HP</label>
                <input type="text" name="nohp" class="form-control" value="{{ $user->nohp ?? '' }}">
              </div>
              <div class="mb-3"><label>Foto</label>
                <input type="file" name="foto" class="form-control">
                @if($user->foto)
                  <small class="text-muted">Foto saat ini: {{ $user->foto }}</small>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-warning">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- MODAL TAMBAH USER --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Anggota Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.datauser.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label>Kategori</label>
            <select id="categorySelect" class="form-control" onchange="toggleFields()" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="siswa">Siswa</option>
              <option value="guru">Guru</option>
              <option value="umum">Pengunjung Umum</option>
            </select>
          </div>

          <div class="mb-3"><label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
          </div>

          <!-- FIELD SISWA -->
          <div id="siswaFields" style="display:none;">
            <div class="mb-3"><label>NISN</label>
              <input type="text" name="nisn" class="form-control">
            </div>
            <div class="mb-3"><label>Asal Sekolah</label>
              <input type="text" name="asal_sekolah" class="form-control">
            </div>
            <div class="mb-3"><label>Kelas</label>
              <input type="text" name="kelas" class="form-control">
            </div>
          </div>

          <!-- FIELD GURU -->
          <div id="guruFields" style="display:none;">
            <div class="mb-3"><label>NIP</label>
              <input type="text" name="nip" class="form-control">
            </div>
            <div class="mb-3"><label>Email</label>
              <input type="email" name="email_guru" class="form-control">
            </div>
            <div class="mb-3"><label>Alamat</label>
              <input type="text" name="alamat_guru" class="form-control">
            </div>
            <div class="mb-3"><label>Tanggal Lahir</label>
              <input type="date" name="tgl_lahir_guru" class="form-control">
            </div>
          </div>

          <!-- FIELD UMUM -->
          <div id="umumFields" style="display:none;">
            <div class="mb-3"><label>Email</label>
              <input type="email" name="email_umum" class="form-control">
            </div>
            <div class="mb-3"><label>Alamat</label>
              <input type="text" name="alamat_umum" class="form-control">
            </div>
            <div class="mb-3"><label>Tanggal Lahir</label>
              <input type="date" name="tgl_lahir_umum" class="form-control">
            </div>
          </div>

          <!-- FIELD UMUM (SEMUA KATEGORI) -->
          <div class="mb-3"><label>Nomor HP</label>
            <input type="text" name="nohp" class="form-control">
          </div>
          <div class="mb-3"><label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3"><label>Foto</label>
            <input type="file" name="foto" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleFields() {
  const category = document.getElementById('categorySelect').value;
  document.getElementById('siswaFields').style.display = category === 'siswa' ? 'block' : 'none';
  document.getElementById('guruFields').style.display = category === 'guru' ? 'block' : 'none';
  document.getElementById('umumFields').style.display = category === 'umum' ? 'block' : 'none';
}
</script>

@endsection
