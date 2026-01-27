@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')

<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --bg-gradient: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);
    }

    body {
        background: #f3f4f6;
        font-family: 'Poppins', sans-serif;
        color: #444;
    }

    /* Kartu Utama */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        background: #fff;
        overflow: hidden;
        animation: fadeIn 0.8s ease;
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(135deg, #2c3e50, #4ca1af);
        padding: 40px 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .header-section::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        z-index: 0;
    }

    .header-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title h3 {
        font-weight: 700;
        margin-bottom: 5px;
        font-size: 1.8rem;
    }

    .header-title p {
        opacity: 0.9;
        margin: 0;
        font-weight: 300;
    }

    /* Stats Box Kecil di Header */
    .stats-box {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 10px 20px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.3);
        text-align: center;
    }

    .stats-box span {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
    }

    .stats-box small {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Styling Tabel Modern */
    .table-container {
        padding: 20px;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px; /* Jarak antar baris */
    }

    .custom-table thead th {
        border: none;
        color: #8898aa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 15px 20px;
    }

    .custom-table tbody tr {
        background: #fff;
        box-shadow: 0 5px 10px rgba(0,0,0,0.02);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        z-index: 10;
        position: relative;
    }

    .custom-table td {
        border: none;
        padding: 20px;
        vertical-align: middle;
        border-top: 1px solid #f1f3f9;
        border-bottom: 1px solid #f1f3f9;
    }

    .custom-table td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        border-left: 1px solid #f1f3f9;
    }

    .custom-table td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
        border-right: 1px solid #f1f3f9;
    }

    /* Ikon Buku */
    .book-icon-wrapper {
        width: 45px;
        height: 45px;
        background: #eef2f7;
        color: #4e73df;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    /* Badge Status Kustom */
    .status-badge {
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-dipinjam {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-kembali {
        background-color: #d4edda;
        color: #155724;
    }

    .status-terlambat {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 4rem;
        color: #d1d3e2;
        margin-bottom: 15px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5">
    <div class="main-card">
        
        <div class="header-section">
            <div class="header-content">
                <div class="header-title">
                    <h3>Riwayat Peminjaman</h3>
                    <p>Kelola dan pantau status buku perpustakaan Anda.</p>
                </div>
                <div class="stats-box d-none d-md-block">
                    <span>{{ count($peminjaman) }}</span>
                    <small>Total Buku</small>
                </div>
            </div>
        </div>

        <div class="table-container table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Nomor Pustaka</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                        @php
                            $statusClass = 'status-dipinjam';
                            $statusIcon = 'bi-clock-history';
                            $statusLabel = 'Dipinjam';

                            if(strtolower($p->status) == 'dikembalikan' || strtolower($p->status) == 'kembali') {
                                $statusClass = 'status-kembali';
                                $statusIcon = 'bi-check-circle-fill';
                                $statusLabel = 'Selesai';
                            } elseif(strtolower($p->status) == 'terlambat') {
                                $statusClass = 'status-terlambat';
                                $statusIcon = 'bi-exclamation-triangle-fill';
                                $statusLabel = 'Terlambat';
                            }
                        @endphp

                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-icon-wrapper">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $p->judul_buku }}</span>
                                        <span class="text-muted small" style="font-size: 0.8rem;">Perpustakaan Utama</span>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-secondary">#{{ $p->nomor_buku }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</span>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('H:i') }} WIB</small>
                                </div>
                            </td>
                            <td>
                                @if($p->tanggal_kembali)
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}</span>
                                        <small class="text-muted">Batas Pengembalian</small>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ $p->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <h5 class="text-muted">Belum ada riwayat peminjaman.</h5>
                                    <p class="text-muted small">Mulai membaca dengan meminjam buku favorit Anda!</p>
                                </div>
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