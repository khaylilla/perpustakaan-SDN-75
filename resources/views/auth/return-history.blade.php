@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')

<style>
    :root {
        --primary-green: #1cc88a;
        --secondary-green: #13855c;
        --bg-gradient: linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%);
    }

    body {
        background: #f4f6f9;
        font-family: 'Poppins', sans-serif;
        color: #444;
    }

    /* Container Utama */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        background: #fff;
        overflow: hidden;
        animation: slideUp 0.8s ease;
    }

    /* Header Section */
    .header-section {
        background: linear-gradient(135deg, #11998e, #38ef7d); /* Gradasi Hijau Segar */
        padding: 40px 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .header-section::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -5%;
        width: 250px;
        height: 250px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        z-index: 0;
        pointer-events: none;
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

    /* Stats Box Kecil */
    .stats-box {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(5px);
        padding: 10px 25px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.4);
        text-align: center;
        color: #fff;
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

    /* Styling Tabel */
    .table-container {
        padding: 20px;
    }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px; /* Jarak antar baris */
    }

    .custom-table thead th {
        border: none;
        color: #8898aa;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        padding: 15px 20px;
    }

    .custom-table tbody tr {
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 10px;
    }

    .custom-table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        position: relative;
        z-index: 5;
    }

    .custom-table td {
        border: none;
        padding: 20px;
        vertical-align: middle;
        border-top: 1px solid #f0f3f5;
        border-bottom: 1px solid #f0f3f5;
    }

    .custom-table td:first-child {
        border-top-left-radius: 15px;
        border-bottom-left-radius: 15px;
        border-left: 1px solid #f0f3f5;
    }

    .custom-table td:last-child {
        border-top-right-radius: 15px;
        border-bottom-right-radius: 15px;
        border-right: 1px solid #f0f3f5;
    }

    /* Ikon Buku */
    .book-icon-wrapper {
        width: 45px;
        height: 45px;
        background: #e6fffa;
        color: #11998e;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 15px;
    }

    /* Badge Status */
    .status-badge {
        background-color: #d1e7dd;
        color: #0f5132;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state i {
        font-size: 3.5rem;
        color: #cbd3da;
        margin-bottom: 15px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5">
    <div class="main-card">
        
        <div class="header-section">
            <div class="header-content">
                <div class="header-title">
                    <h3>Riwayat Pengembalian</h3>
                    <p>Arsip buku yang telah Anda baca dan kembalikan.</p>
                </div>
                <div class="stats-box d-none d-md-block">
                    <span>{{ count($pengembalian) }}</span>
                    <small>Buku Selesai</small>
                </div>
            </div>
        </div>

        <div class="table-container table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Nomor Pustaka</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="book-icon-wrapper">
                                        <i class="bi bi-journal-check"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block">{{ $p->judul_buku }}</span>
                                        <span class="text-muted small">Koleksi Perpustakaan</span>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-secondary">#{{ $p->nomor_buku }}</td>
                            <td>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-success">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}</span>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('H:i') }} WIB
                                    </small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="status-badge">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Dikembalikan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h5 class="text-muted fw-bold">Belum ada data pengembalian.</h5>
                                    <p class="text-muted small">Buku yang Anda pinjam akan muncul di sini setelah dikembalikan.</p>
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