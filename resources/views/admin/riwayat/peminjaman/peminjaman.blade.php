@extends('admin.layout')

@section('page-title', 'Manajemen Peminjaman Buku')

@section('content')
{{-- STYLE TAMBAHAN --}}
<style>
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
  }

  .info-box {
    background: linear-gradient(135deg, #4a4ca4, #6c6ef8);
    color: white;
    border-radius: 16px;
    width: 280px;
    padding: 16px 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: transform 0.2s ease;
  }

  .info-box:hover { transform: translateY(-3px); }
  .info-box i { font-size: 32px; opacity: 0.7; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin: 3px 0 0 0; }

  .table-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    overflow-x: auto;
    margin-top: 10px;
    padding: 15px;
  }

  table { width: 100%; border-collapse: collapse; min-width: 900px; }
  table th, table td {
    padding: 10px;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd;
  }
  table th { background-color: #f2f2f2; }
  table tbody tr:hover { background-color: #f6f6ff; }
  .badge { font-size: 0.85em; }

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
</style>

<div class="container-fluid">

  {{-- INFO BOXES --}}
  <div class="info-boxes">
    <a href="{{ route('admin.riwayat.peminjaman.scan') }}" class="info-box" style="background: linear-gradient(135deg, #f7931e, #ffa94d); color:white; width: 320px; padding: 20px 24px;">
      <div class="info-box-content">
        <h5>Scan Peminjaman Buku</h5>
        <p>Scan barcode anggota & buku</p>
      </div>
      <i class="bi bi-upc-scan"></i>
    </a>

    <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="info-box" style="background: linear-gradient(135deg, #f7931e, #ffa94d); color:white; width: 320px; padding: 20px 24px;">
      <div class="info-box-content">
        <h5>Data Peminjaman Buku</h5>
        <p>Daftar peminjaman yang sedang berlangsung</p>
      </div>
      <i class="bi bi-book-half"></i>
    </a>
  </div>

 {{-- SEARCH BAR --}}
    <form action="{{ route('admin.riwayat.peminjaman.peminjaman') }}" method="GET" class="search-bar w-100 mb-3">
        <i class="bi bi-search"></i>
        <input type="text" name="keyword" id="searchInput" placeholder="Cari Peminjaman..." value="{{ request('keyword') }}">
        <button type="submit" class="btn btn-primary btn-sm ms-2">Cari</button>
    </form>
    
  {{-- SORT & GROUP BAR --}}
  <div class="filter-sort-bar">
  <input type="date" id="filter-date" class="form-control"> <br>

  <a href="{{ route('admin.riwayat.peminjaman.pdf') }}" id="downloadPdf" class="btn btn-success">Download PDF</a>
</div>


  {{-- TABEL PEMINJAMAN --}}
  <div class="table-container">
    <table class="table text-center align-middle mb-0">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Anggota</th>
          <th>NPM</th>
          <th>Judul Buku</th>
          <th>Nomor Buku</th>
          <th>Tanggal Pinjam</th>
          <th>Tanggal Kembali</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peminjaman->where('status', 'dipinjam') as $index => $p)
          @php
            $hariIni = \Carbon\Carbon::now();
            $tanggalKembali = $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali) : null;
            $telat = $tanggalKembali ? ($hariIni->gt($tanggalKembali) && strtolower($p->status) === 'dipinjam') : false;
          @endphp

          <tr data-status="{{ strtolower($p->status) }}" data-date="{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}" @if($telat) style="background-color:#f8d7da;" @endif>
            <td>{{ $index + 1 }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->npm }}</td>
            <td>{{ $p->judul_buku }}</td>
            <td>{{ $p->nomor_buku }}</td>
            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
            <td>
              @if($p->tanggal_kembali)
                {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}
              @else
                <span class="text-muted">Belum kembali</span>
              @endif
            </td>
            <td>
              @if($telat)
                <span class="badge bg-danger text-white">Terlambat</span>
              @elseif(strtolower($p->status) === 'dipinjam')
                <span class="badge bg-warning text-dark">{{ $p->status }}</span>
              @else
                <span class="badge bg-success">{{ $p->status }}</span>
              @endif
            </td>
            <td class="text-center">
              <a href="javascript:;" 
                class="btn-edit text-primary" 
                data-id="{{ $p->id }}" 
                data-nama="{{ $p->nama }}"
                data-npm="{{ $p->npm }}"
                data-judul="{{ $p->judul_buku }}"
                data-nomor="{{ $p->nomor_buku }}"
                data-status="{{ $p->status }}"
                title="Edit Peminjaman">
                  <i class="bi bi-pencil"></i>
              </a>

              <form action="{{ route('destroy', $p->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 border-0 bg-transparent" title="Hapus Peminjaman">
                  <i class="bi bi-trash text-danger"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center text-muted">Belum ada data peminjaman</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- MODAL EDIT PEMINJAMAN --}}
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Peminjaman</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-2">
              <label>Nama Anggota</label>
              <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>NPM</label>
              <input type="text" name="npm" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Judul Buku</label>
              <input type="text" name="judul_buku" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Nomor Buku</label>
              <input type="text" name="nomor_buku" class="form-control" required>
            </div>
            <div class="mb-2">
              <label>Status</label>
              <select name="status" class="form-select">
                <option value="Dipinjam">Dipinjam</option>
                <option value="dikembalikan">Dikembalikan</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>

{{-- SCRIPT TAMBAHAN --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Modal edit
  const editButtons = document.querySelectorAll('.btn-edit');
  const editForm = document.getElementById('editForm');

  editButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const data = this.dataset;
      editForm.action = `/admin/riwayat/peminjaman/update/${data.id}`;
      editForm.nama.value = data.nama;
      editForm.npm.value = data.npm;
      editForm.judul_buku.value = data.judul;
      editForm.nomor_buku.value = data.nomor;
      editForm.status.value = data.status;
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
  });

  // Filter berdasarkan tanggal dan status + Cetak PDF
  const dateInput = document.getElementById('filter-date');
  const downloadPdf = document.getElementById('downloadPdf');

  function applyFilters() {
    const rows = document.querySelectorAll('tbody tr');
    const selectedDate = dateInput.value ? new Date(dateInput.value) : null;
    const selectedStatus = statusSelect.value.toLowerCase();
    let visibleCount = 0;

    rows.forEach(row => {
      // Abaikan row "tidak ada data" jika sudah ada
      if (row.classList.contains('no-data-row')) return;

      const rowDate = new Date(row.dataset.date);
      const rowStatus = row.dataset.status;
      let visible = true;

      // Filter tanggal
      if (selectedDate && rowDate.toDateString() !== selectedDate.toDateString()) visible = false;
      // Filter status
      if (selectedStatus && rowStatus !== selectedStatus) visible = false;

      row.style.display = visible ? '' : 'none';

      if (visible) visibleCount++;
    });

    // Tambahkan row "Tidak ada data yang ditemukan" jika tidak ada baris yang terlihat
    let tbody = document.querySelector('tbody');
    let noDataRow = tbody.querySelector('.no-data-row');

    if (visibleCount === 0) {
      if (!noDataRow) {
        noDataRow = document.createElement('tr');
        noDataRow.classList.add('no-data-row');
        noDataRow.innerHTML = `<td colspan="9" class="text-center text-muted">Tidak ada data yang ditemukan</td>`;
        tbody.appendChild(noDataRow);
      }
    } else {
      if (noDataRow) noDataRow.remove();
    }
  }

  // Jalankan filter saat tanggal/status diubah
  dateInput.addEventListener('change', applyFilters);
  statusSelect.addEventListener('change', applyFilters);

  // Tombol download PDF
  downloadPdf.addEventListener('click', function () {
  const date = dateInput.value;
  let url = `{{ route('admin.riwayat.peminjaman.pdf') }}?filter_date=${date}`;
  window.open(url, '_blank');
});

  // Jalankan filter awal saat halaman dimuat
  applyFilters();
});
</script>
@endsection
