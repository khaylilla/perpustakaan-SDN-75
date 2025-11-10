@extends('admin.layout')

@section('page-title', 'Manajemen Pengembalian Buku')

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

  /* SORT & FILTER BAR */
  .sort-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
  }
  .sort-filter-bar select {
    width: auto;
  }
</style>

<div class="container-fluid">

  {{-- INFO BOXES --}}
  <div class="info-boxes">
    <a href="{{ route('admin.riwayat.pengembalian.scankembali') }}" class="info-box" style="background: linear-gradient(135deg, #f7931e, #ffa94d); color:white; width: 320px; padding: 20px 24px;">
      <div class="info-box-content">
        <h5>Scan Pengembalian Buku</h5>
        <p>Scan barcode anggota & buku</p>
      </div>
      <i class="bi bi-upc-scan"></i>
    </a>

    <a href="{{ route('admin.riwayat.pengembalian.pengembalian') }}" class="info-box" style="background: linear-gradient(135deg, #f7931e, #ffa94d); color:white; width: 320px; padding: 20px 24px;">
      <div class="info-box-content">
        <h5>Data Pengembalian Buku</h5>
        <p>Daftar pengembalian yang sedang berlangsung</p>
      </div>
      <i class="bi bi-book-half"></i>
    </a>
  </div>

{{-- SORT & FILTER BAR --}}
  <div class="sort-filter-bar">
    <label for="filter-date" class="fw-semibold">Pilih Tanggal:</label>
    <input type="date" id="filter-date" class="form-control">

    <label for="status-filter" class="fw-semibold ms-3">Status:</label>
    <select id="status-filter" class="form-select">
      <option value="">Semua</option>
      <option value="Dipinjam">Dipinjam</option>
      <option value="dikembalikan">Dikembalikan</option>
    </select>

    <a href="admin.riwayat.pengembalian.pdfkembali" id="downloadPdf" class="btn btn-success ms-auto">
      <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
    </a>
  </div>

  {{-- TABEL PENGEMBALIAN --}}
  <div class="table-container">
    <table class="table text-center align-middle mb-0" id="tablePengembalian">
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
        @forelse($peminjaman as $index => $p)
        <tr data-status="{{ strtolower($p->status) }}">
          <td>{{ $index + 1 }}</td>
          <td>{{ $p->nama }}</td>
          <td>{{ $p->npm }}</td>
          <td>{{ $p->judul_buku }}</td>
          <td>{{ $p->nomor_buku }}</td>
          <td data-date="{{ $p->tanggal_pinjam }}">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
          <td>
            @if($p->tanggal_kembali)
              {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}
            @else
              <span class="text-muted">Belum kembali</span>
            @endif
          </td>
          <td>
            @if(strtolower($p->status) === 'dipinjam')
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
              title="Edit Pengembalian">
                <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('admin.riwayat.pengembalian.destroy', $p->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn p-0 border-0 bg-transparent" title="Hapus Pengembalian">
                <i class="bi bi-trash text-danger"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" class="text-center text-muted">Belum ada data pengembalian</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- MODAL EDIT PENGEMBALIAN --}}
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header bg-warning text-white">
            <h5 class="modal-title">Edit Pengembalian</h5>
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
  const table = document.getElementById('tablePengembalian');
  const dateInput = document.getElementById('filter-date');
  const statusFilter = document.getElementById('status-filter');
  const pdfButton = document.getElementById('downloadPdf');

  // FILTER LOGIC
  function applyFilters() {
    const selectedDate = dateInput.value ? new Date(dateInput.value) : null;
    const selectedStatus = statusFilter.value.toLowerCase();

    table.querySelectorAll('tbody tr').forEach(row => {
      const rowDate = new Date(row.dataset.date);
      const rowStatus = row.dataset.status;
      let visible = true;

      if (selectedDate && rowDate.toDateString() !== selectedDate.toDateString()) visible = false;
      if (selectedStatus && rowStatus !== selectedStatus) visible = false;

      row.style.display = visible ? '' : 'none';
    });
  }

  dateInput.addEventListener('change', applyFilters);
  statusFilter.addEventListener('change', applyFilters);

  // CETAK PDF
  pdfButton.addEventListener('click', function () {
    const date = dateInput.value;
    const status = statusFilter.value;
    const url = `{{ route('admin.riwayat.pengembalian.pdfkembali') }}?filter_date=${date}&filter_status=${status}`;
    window.open(url, '_blank');
  });

  // Modal Edit
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {
      const data = this.dataset;
      const form = document.getElementById('editForm');
      form.action = `/admin/riwayat/pengembalian/update/${data.id}`;
      form.nama.value = data.nama;
      form.npm.value = data.npm;
      form.judul_buku.value = data.judul;
      form.nomor_buku.value = data.nomor;
      form.status.value = data.status;
      new bootstrap.Modal(document.getElementById('editModal')).show();
    });
  });
});
</script>
@endsection
