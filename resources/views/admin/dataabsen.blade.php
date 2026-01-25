@extends('admin.layout')

@section('page-title', 'Manajemen Data Absen & Cetak Kartu')

@section('content')
<style>

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
    max-width: 80%;
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
  .page-title { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
  .page-title h4 { font-weight:700; color:#2e2e2e; }
  .btn-add { background-color:#4a4ca4; color:#fff; border-radius:8px; font-weight:600; padding:10px 16px; }
  .btn-add:hover { background-color:#3c3f91; color:#fff; }
  .search-bar { background:white; border-radius:12px; box-shadow:0 3px 8px rgba(0,0,0,0.08); display:flex; align-items:center; padding:10px 15px; margin-bottom:20px; width:100%; max-width:80%; }
  .search-bar input { border:none; outline:none; flex:1; font-size:15px; padding-left:8px; }
  .search-bar .btn { white-space:nowrap; }
  .table-container { background-color:white; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1); padding:0; overflow-x:auto; }
  table { margin-bottom:0; min-width:900px; }
  thead { background-color:#e7e8fc; color:#333; }
  th, td { vertical-align: middle !important; white-space: nowrap; }
  .table tbody tr:hover { background-color: #f6f6ff; }
  .action-icons i { font-size:18px; cursor:pointer; margin:0 6px; }
  .action-icons .view { color:#0066ff; }
  .action-icons .edit { color:#f39c12; }
  .action-icons .delete { color:#e74c3c; }

  {{-- Print Styles --}}
  @media print {
    body { background: none; }
    .header-cards { display: none !important; }
    .filters { display: none !important; }
    .table-container { display: none !important; }
    .btn-add { display: none !important; }
    .modal { display: none !important; }
  }
</style>

<div class="container-fluid">

  {{-- HEADER CARDS --}}
  <div class="header-cards d-flex flex-wrap gap-3 mb-4">

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

    {{-- 3 KOTAK KECIL --}}
    <div class="d-flex flex-wrap gap-3">

      <a href="{{ route('admin.absen.scan') }}" 
         class="text-decoration-none"
         style="width: 180px;">
        <div class="card shadow-sm border-0 text-white"
             style="background: #ff9f43; border-radius: 14px;">
          <div class="card-body text-center p-3">
            <i class="bi bi-qr-code-scan fs-2 d-block mb-2"></i>
            <p class="fw-bold mb-0">Scan Absen</p>
          </div>
        </div>
      </a>

      <a href="{{ route('admin.dataabsen') }}" 
         class="text-decoration-none"
         style="width: 180px;">
        <div class="card shadow-sm border-0 text-white"
             style="background: #ff9f43; border-radius: 14px;">
          <div class="card-body text-center p-3">
            <i class="bi bi-table fs-2 d-block mb-2"></i>
            <p class="fw-bold mb-0">Data Absen</p>
          </div>
        </div>
      </a>

      <a href="{{ route('admin.kartu') }}" 
         class="text-decoration-none"
         style="width: 180px;">
        <div class="card shadow-sm border-0 text-white"
             style="background: #ff9f43; border-radius: 14px;">
          <div class="card-body text-center p-3">
            <i class="bi bi-credit-card fs-2 d-block mb-2"></i>
            <p class="fw-bold mb-0">Kartu Anggota</p>
          </div>
        </div>
      </a>

    </div>

  </div>

    {{-- BUTTON TAMBAH --}}
    <div class="d-flex align-items-center ms-auto mb-3">
      <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#createAbsenModal">
        + Tambah Absen
      </button>
    </div>
  </div>

  {{-- SEARCH BAR --}}
  <form action="{{ route('admin.dataabsen') }}" method="GET" class="search-bar w-100">
    <i class="bi bi-search"></i>
    <input type="text" name="keyword" placeholder="Cari nama anggota, NISN/NIP/Email, atau tanggal..." value="{{ request('keyword') }}">
    <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
  </form>

  {{-- ALERT --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- FILTER DAN PRINT --}}
  <div class="filters d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form action="{{ route('admin.dataabsen') }}" method="GET" class="d-flex align-items-center gap-2 mb-0 flex-wrap">

        {{-- FILTER TIPE USER (DROPDOWN) --}}
        <label class="fw-semibold mb-0">Filter Tipe:</label>
        <select name="type" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 140px;">
          <option value="">-- Semua --</option>
          <option value="siswa" {{ request('type') === 'siswa' ? 'selected' : '' }}>Siswa</option>
          <option value="guru" {{ request('type') === 'guru' ? 'selected' : '' }}>Guru</option>
          <option value="umum" {{ request('type') === 'umum' ? 'selected' : '' }}>Umum</option>
        </select>

        {{-- FILTER TANGGAL --}}
        <label class="fw-semibold mb-0">Filter Tanggal:</label>

        <input type="date" name="start_date" class="form-control form-control-sm"
              value="{{ request('start_date') }}"
              style="width: 160px;">

        <span class="fw-bold">s/d</span>

        <input type="date" name="end_date" class="form-control form-control-sm"
              value="{{ request('end_date') }}"
              style="width: 160px;">

        <button class="btn btn-primary btn-sm ms-2">Terapkan</button>

      </form>

    <div class="d-flex gap-2">
      <a href="{{ route('admin.dataabsen.export', ['groupBy' => 'day']) }}" class="btn btn-danger">
          <i class="bi bi-file-earmark-pdf"></i> Export PDF
      </a>
    </div>
  </div>

{{-- TABEL DATA --}}
<div class="table-container">
  <table class="table text-center align-middle mb-4">
    <thead>
      <tr>
        <th style="width:60px;">No</th>
        <th>Nama</th>
        <th>NISN/NIP/Email</th>
        <th>Tanggal Absen</th>
        <th style="width:120px;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($absens as $i => $absen)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $absen->nama }}</td>
          <td>{{ $absen->npm }}</td>
          <td>{{ $absen->tanggal }}</td>
          <td class="action-icons">
            <!-- Edit -->
            <i class="bi bi-pencil-square edit" data-bs-toggle="modal" data-bs-target="#editAbsenModal{{ $absen->id }}" title="Edit"></i>
            <!-- Delete -->
            <form action="{{ route('admin.dataabsen.delete', $absen->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn p-0 border-0 bg-transparent" onclick="return confirm('Yakin hapus absen ini?')">
                <i class="bi bi-trash delete" title="Hapus"></i>
              </button>
            </form>
          </td>
        </tr>

        {{-- MODAL EDIT --}}
        <div class="modal fade" id="editAbsenModal{{ $absen->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Absen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form action="{{ route('admin.dataabsen.update', $absen->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                  <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $absen->nama }}" required>
                  </div>
                  <div class="mb-3">
                    <label>NISN/NIP/Email</label>
                    <input type="text" name="npm" class="form-control" value="{{ $absen->npm }}" required>
                  </div>
                  <div class="mb-3">
                    <label>Tanggal Absen</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $absen->tanggal }}" required>
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

      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">Belum ada data absen</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- MODAL TAMBAH ABSEN --}}
<div class="modal fade" id="createAbsenModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Tambah Absen Baru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.dataabsen.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label>Pilih Nama dari Database</label>
            <select name="person_id" id="personSelect" class="form-control" required onchange="updatePersonData()">
              <option value="">-- Pilih Nama --</option>
              @foreach($allPersons as $person)
                <option value="{{ $person->id }}" data-type="{{ $person->type }}" data-nama="{{ $person->nama }}" 
                  @if($person->type === 'users')
                    data-identifier="{{ $person->nisn ?? '' }}"
                  @elseif($person->type === 'guru')
                    data-identifier="{{ $person->nip ?? '' }}"
                  @else
                    data-identifier="{{ $person->email ?? '' }}"
                  @endif
                >
                  {{ $person->nama }} 
                  ({{ $person->type === 'users' ? 'Siswa' : ($person->type === 'guru' ? 'Guru' : 'Umum') }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Nama (Manual)</label>
            <input type="text" name="nama" id="namaInput" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>NISN / NIP / Email</label>
            <input type="text" name="npm" id="npmInput" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Tanggal Absen</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
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

function printKartu() {
  // Get current filter type
  const urlParams = new URLSearchParams(window.location.search);
  const type = urlParams.get('type') || '';
  const keyword = urlParams.get('keyword') || '';
  const startDate = urlParams.get('start_date') || '';
  const endDate = urlParams.get('end_date') || '';
  
  // Build query string
  let queryStr = '';
  if (type) queryStr += `&type=${type}`;
  if (keyword) queryStr += `&keyword=${keyword}`;
  if (startDate) queryStr += `&start_date=${startDate}`;
  if (endDate) queryStr += `&end_date=${endDate}`;
  
  // Open print window with kartu
  const printWindow = window.open(
    `{{ route('admin.kartu') }}?print=1${queryStr}`,
    'printKartu',
    'width=900,height=1200'
  );
  
  printWindow.onload = function() {
    printWindow.print();
  };
}
</script>
@endsection