@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')

<style>
    body {
        background: #c3cfe2ff;
        font-family: 'Poppins', sans-serif;
    }

    /* =============== HEADER =============== */
    .borrow-header {
        background: #ffffff;
        border-radius: 20px;
        padding: 25px 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .borrow-header-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: linear-gradient(135deg, #0c1a33, #1a2f52);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }

    .borrow-header h3 {
        margin: 0;
        font-weight: 800;
        color: #0c1a33;
        font-size: 1.3rem;
    }

    .borrow-header p {
        margin: 0;
        margin-top: -2px;
        font-size: 0.9rem;
        color: rgba(10, 35, 66, 0.65);
    }

    /* =============== TABLE CARD =============== */
    .borrow-table-wrapper {
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .borrow-table-title {
        background: linear-gradient(135deg, #0c1a33, #1a2f52);
        padding: 22px 28px;
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .borrow-table table {
        margin: 0;
        width: 100%;
    }

    .borrow-table th {
        background: #ffffff;
        font-weight: 700;
        padding: 15px;
        font-size: 0.9rem;
        border-bottom: 1px solid #e6e9ef;
        white-space: nowrap;
    }

    .borrow-table td {
        padding: 18px;
        font-size: 0.92rem;
        border-bottom: 1px solid #eceff3;
        vertical-align: middle;
    }

    .borrow-table tbody tr:hover {
        background: #f6f8fc;
    }

    .borrow-icon {
        width: 42px;
        height: 42px;
        background: #eef4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
        color: #102544;
    }

    .borrow-status {
        background: #0c1a33;
        color: white;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }
</style>

<div class="container py-4">

    <!-- ================= HEADER ================= -->
    <div class="borrow-header">
        <div class="borrow-header-icon">
            📖
        </div>
        <div>
            <h3>Riwayat Peminjaman</h3>
            <p>Buku yang sedang Anda pinjam ({{ count($peminjaman) }} buku)</p>
        </div>
    </div>

    <!-- ================= MAIN TABLE ================= -->
    <div class="borrow-table-wrapper">
        <div class="borrow-table-title">
            Daftar Buku Dipinjam
        </div>

        <div class="borrow-table table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th><i class="bi bi-book"></i> Judul Buku</th>
                    <th># Nomor Buku</th>
                    <th><i class="bi bi-calendar"></i> Tanggal Peminjaman</th>
                    <th><i class="bi bi-calendar-check"></i> Tanggal Pengembalian</th> <!-- baru -->
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="borrow-icon">📗</div>
                            <span>{{ $p->judul_buku }}</span>
                        </div>
                    </td>
                    <td>{{ $p->nomor_buku }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}</td>
                    <td>
                        {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('Y-m-d') : '-' }}
                    </td>
                    <td>
                        <span class="borrow-status">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        Belum ada peminjaman
                    </td>
                </tr>
                @endforelse
            </tbody>

            </table>
        </div>
    </div>

</div>
@include('components.footer')
@endsection
