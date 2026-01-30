@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --primary-blue: #0A58CA;
        --deep-navy: #021f4b;
        --accent-red: #d90429;
        --pure-white: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --font-heading: 'Outfit', sans-serif;
        --font-body: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        background-color: var(--pure-white);
        color: var(--text-main);
        font-family: var(--font-body);
        overflow-x: hidden;
    }

    /* ANIMASI BACKGROUND BUBBLES */
    .bg-animated {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        z-index: -1; background: #fff; overflow: hidden;
    }
    .bubble {
        position: absolute; border-radius: 50%; filter: blur(60px); opacity: 0.1; animation: float 20s infinite alternate;
    }
    .bubble-1 { width: 400px; height: 400px; background: var(--primary-blue); top: -100px; right: -100px; }
    .bubble-2 { width: 300px; height: 300px; background: var(--accent-red); bottom: -50px; left: -50px; animation-delay: -5s; }
    
    @keyframes float {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(50px, 100px) scale(1.1); }
    }

    /* Hero Section */
    .hero-mini {
        padding: 40px 0 20px;
        text-align: center;
    }
    .hero-mini h3 {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--deep-navy) 30%, var(--primary-blue) 60%, var(--accent-red) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Main Card Styling */
    .main-card {
        border: none;
        border-radius: 35px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 20px 40px rgba(2, 31, 75, 0.05);
        overflow: hidden;
        margin-bottom: 80px;
    }

    /* Header Tabel */
    .header-section {
        background: var(--deep-navy);
        padding: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stats-pill {
        background: rgba(255,255,255,0.1);
        padding: 8px 20px;
        border-radius: 15px;
        border: 1px solid rgba(255,255,255,0.2);
        font-weight: 700;
    }

    /* Custom Table Row Pop-Up */
    .table-container { padding: 25px; }
    
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .custom-table thead th {
        border: none;
        color: var(--deep-navy);
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        padding: 10px 20px;
    }

    .custom-table tbody tr {
        background: var(--pure-white);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }

    .custom-table tbody tr:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 15px 30px rgba(2, 31, 75, 0.1);
        z-index: 5;
    }

    .custom-table td {
        padding: 20px;
        border: none;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }

    .custom-table td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 20px; border-bottom-left-radius: 20px; }
    .custom-table td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 20px; border-bottom-right-radius: 20px; }

    /* Ikon Buku */
    .book-icon-wrapper {
        width: 48px; height: 48px;
        background: #f1f5f9;
        color: var(--primary-blue);
        border-radius: 15px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin-right: 15px;
        transition: 0.3s;
    }
    .custom-table tr:hover .book-icon-wrapper {
        background: var(--primary-blue);
        color: white;
    }

    /* Badge Status (Menyala) */
    .status-badge {
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-dipinjam { background: #fff8e1; color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-kembali { background: #ecfdf5; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-terlambat { background: #fef2f2; color: var(--accent-red); border: 1px solid rgba(217, 4, 41, 0.2); }

    .empty-state {
        text-align: center; padding: 80px 20px;
    }
    .empty-state i { font-size: 5rem; color: var(--text-muted); opacity: 0.2; }
</style>

<div class="bg-animated">
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
</div>

<div class="container">
    <div class="hero-mini" data-aos="fade-down">
        <h3>Riwayat Peminjaman</h3>
        <p class="text-muted">Pantau aktivitas literasi dan status koleksi Anda.</p>
    </div>

    <div class="main-card" data-aos="fade-up">
        <div class="header-section">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-book-fill fs-4 text-primary"></i>
                <span class="fw-bold">Daftar Peminjaman Aktif</span>
            </div>
            <div class="stats-pill">
                {{ count($peminjaman) }} Buku Dipinjam
            </div>
        </div>

        <div class="table-container table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama</th>
                        <th>Identitas</th>
                        <th>Judul Buku</th>
                        <th>Nomor Buku</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Tanggal Pinjam</th>
                        <th class="text-center">Tenggat Kembali</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $index => $p)
                        @php
                            $tenggat = $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali) : null;
                            $isOverdue = $tenggat ? \Carbon\Carbon::now()->gt($tenggat) : false;
                        @endphp
                        <tr>
                            <td class="text-center">
                                <span class="fw-bold text-muted small">{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->nama }}</div>
                            </td>
                            <td>
                                <div class="text-primary fw-semibold" style="font-size: 11px;">{{ $p->npm }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13px;">{{ $p->judul_buku }}</div>
                            </td>
                            <td>
                                <div class="text-muted small">{{ $p->nomor_buku }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="background: #f0f9ff; color: #0A58CA;">{{ $p->jumlah ?? 1 }}</span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-danger">{{ $tenggat ? $tenggat->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="text-center">
                                @if($isOverdue)
                                    <span class="status-badge status-terlambat">
                                        <i class="bi bi-exclamation-octagon"></i>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="status-badge status-dipinjam">
                                        <i class="bi bi-clock-history"></i>
                                        Dipinjam
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                Belum ada riwayat peminjaman
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true });</script>
@endsection