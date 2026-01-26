@extends('admin.layout')
@section('page-title', 'Dashboard Overview')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #4e73df;
        --secondary: #858796;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --dark: #2e344e;
        --light: #f8f9fc;
        --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --hover-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    body {
        background-color: #f0f2f5;
        font-family: 'Poppins', sans-serif;
        color: var(--dark);
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-entry {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0; /* Mulai invisible */
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    /* ===== FILTER BAR ===== */
    .filter-card {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .form-select-custom, .form-control-custom {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.85rem;
        transition: 0.3s;
        background-color: #f8f9fc;
    }

    .form-select-custom:focus, .form-control-custom:focus {
        border-color: var(--primary);
        background-color: white;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        outline: none;
    }

    .btn-filter {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 500;
        transition: 0.3s;
    }
    
    .btn-filter:hover {
        background: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    /* ===== DASHBOARD CARDS (GRID SYSTEM) ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 24px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.02);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--hover-shadow);
    }

    /* Decorative Circle Background */
    .stat-card::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.1;
        transition: 0.4s;
    }

    .stat-card:hover::before {
        transform: scale(1.5);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 15px;
        color: white;
    }

    .stat-title {
        font-size: 0.85rem;
        color: #888;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 5px;
        color: #333;
    }

    /* Card Variants */
    .card-blue .stat-icon { background: linear-gradient(135deg, #4e73df, #224abe); box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3); }
    .card-blue::before { background: #4e73df; }
    
    .card-purple .stat-icon { background: linear-gradient(135deg, #8e44ad, #6c3483); box-shadow: 0 4px 10px rgba(142, 68, 173, 0.3); }
    .card-purple::before { background: #8e44ad; }

    .card-orange .stat-icon { background: linear-gradient(135deg, #f6c23e, #e67e22); box-shadow: 0 4px 10px rgba(246, 194, 62, 0.3); }
    .card-orange::before { background: #f6c23e; }

    .card-green .stat-icon { background: linear-gradient(135deg, #1cc88a, #13855c); box-shadow: 0 4px 10px rgba(28, 200, 138, 0.3); }
    .card-green::before { background: #1cc88a; }

    .card-red .stat-icon { background: linear-gradient(135deg, #e74a3b, #c0392b); box-shadow: 0 4px 10px rgba(231, 74, 59, 0.3); }
    .card-red::before { background: #e74a3b; }


    /* ===== CHART CONTAINERS ===== */
    .chart-box {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        height: 100%;
        transition: 0.3s;
        border: 1px solid rgba(0,0,0,0.03);
    }
    
    .chart-box:hover {
        box-shadow: var(--hover-shadow);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #444;
    }

    /* ===== TABLE STYLING ===== */
    .modern-table-container {
        background: white;
        border-radius: 16px;
        padding: 0;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,0.03);
    }

    .modern-table-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }

    .table-responsive {
        padding: 0 10px 15px;
    }

    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate; 
        border-spacing: 0 5px; /* Jarak antar baris */
    }

    .table thead th {
        border: none;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #888;
        font-weight: 600;
        padding: 15px 15px;
    }

    .table tbody tr {
        background: #fff;
        transition: 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
        transform: scale(1.005);
    }

    .table td {
        border: none;
        padding: 15px;
        vertical-align: middle;
        font-size: 0.9rem;
        border-bottom: 1px solid #f8f9fc;
    }

    /* Badge/Pills for Rank */
    .rank-badge {
        width: 25px;
        height: 25px;
        background: #eef2f8;
        color: #666;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .rank-1 { background: #ffd700; color: #fff; box-shadow: 0 2px 5px rgba(255, 215, 0, 0.4); }
    .rank-2 { background: #c0c0c0; color: #fff; }
    .rank-3 { background: #cd7f32; color: #fff; }

</style>

<div class="container-fluid px-4 py-4">

    <div class="animate-entry">
        <form method="GET" class="filter-card">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-bold text-secondary small text-uppercase">Filter:</span>
            </div>
            
            <select name="mode" class="form-select-custom" style="width:140px">
                <option value="hari" {{ request('mode')=='hari' ? 'selected' : '' }}>Hari Ini</option>
                <option value="bulan" {{ request('mode')=='bulan' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="tahun" {{ request('mode')=='tahun' ? 'selected' : '' }}>Tahun Ini</option>
                <option value="range_tahun" {{ request('mode')=='range_tahun' ? 'selected' : '' }}>Rentang Tahun</option>
            </select>

            <select name="kelas" class="form-select-custom" style="width:160px">
                <option value="">-- Semua Kelas --</option>
                @foreach($semuaKelas as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <input type="date" name="start" class="form-control-custom" value="{{ request('start') }}">
            <span class="text-muted">-</span>
            <input type="date" name="end" class="form-control-custom" value="{{ request('end') }}">

            <button class="btn-filter"><i class="fas fa-search me-1"></i> Terapkan</button>
        </form>
    </div>

    <div class="stats-grid animate-entry delay-1">
        <div class="stat-card card-blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-title">Total Pengunjung</div>
            <div class="stat-value">{{ number_format($totalPengunjung) }}</div>
        </div>

        <div class="stat-card card-purple">
            <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-title">Total User</div>
            <div class="stat-value">{{ number_format($totalUser) }}</div>
        </div>

        <div class="stat-card card-orange">
            <div class="stat-icon"><i class="fas fa-book"></i></div>
            <div class="stat-title">Koleksi Buku</div>
            <div class="stat-value">{{ number_format($totalBuku) }}</div>
        </div>

        <div class="stat-card card-green">
            <div class="stat-icon"><i class="fas fa-book-reader"></i></div>
            <div class="stat-title">Sedang Dipinjam</div>
            <div class="stat-value">{{ number_format($totalPeminjaman) }}</div>
        </div>

        <div class="stat-card card-red">
            <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
            <div class="stat-title">Dikembalikan</div>
            <div class="stat-value">{{ number_format($totalPengembalian) }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-4 animate-entry delay-2">
            <div class="chart-box">
                <div class="section-title"><i class="fas fa-chart-line text-primary"></i> Tren Peminjaman & Pengembalian</div>
                <div style="height: 300px;">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4 animate-entry delay-2">
            <div class="chart-box">
                <div class="section-title"><i class="fas fa-chart-pie text-warning"></i> Kategori Buku</div>
                <div style="height: 300px; position: relative;">
                    <canvas id="chartKategori"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 animate-entry delay-3">
        <div class="col-md-6 mb-4">
            <div class="chart-box">
                <div class="section-title"><i class="fas fa-user-clock text-success"></i> Aktivitas User</div>
                <canvas id="chartUserAktif" height="150"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="chart-box">
                <div class="section-title"><i class="fas fa-layer-group text-info"></i> Distribusi Kelas</div>
                <canvas id="chartSiswaKelas" height="150"></canvas>
            </div>
        </div>
    </div>

    <div class="row animate-entry delay-4">
        <div class="col-lg-6 mb-4">
            <div class="modern-table-container">
                <div class="modern-table-header">
                    <div class="section-title mb-0"><i class="fas fa-crown text-warning"></i> 5 Buku Terpopuler</div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10%">Rank</th>
                                <th>Judul Buku</th>
                                <th width="25%" class="text-end">Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukuFavorit as $index => $buku)
                            <tr>
                                <td>
                                    <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="fw-bold text-dark">{{ $buku->judul_buku }}</td>
                                <td class="text-end"><span class="badge bg-light text-dark border">{{ $buku->total }}x</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="modern-table-container">
                <div class="modern-table-header">
                    <div class="section-title mb-0"><i class="fas fa-medal text-primary"></i> 5 User Terrajin</div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10%">Rank</th>
                                <th>Nama User</th>
                                <th width="25%" class="text-end">Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userAktif as $index => $user)
                            <tr>
                                <td>
                                    <div class="rank-badge {{ $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : '')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="fw-bold text-dark">{{ $user->nama }}</td>
                                <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary">{{ $user->total }} Transaksi</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari Controller
    const labels = @json($labels);
    const peminjamanData = @json(array_values($grafikPeminjaman));
    const pengembalianData = @json(array_values($grafikPengembalian));
    const userAktifData = @json(array_values($grafikUserAktif));
    const kategoriLabels = {!! json_encode($kategoriBuku->keys()) !!};
    const kategoriValues = {!! json_encode($kategoriBuku->values()) !!};
    const kelasLabels = {!! json_encode($siswaPerKelas->keys()) !!};
    const kelasValues = {!! json_encode($siswaPerKelas->values()) !!};
    const bulanDefault = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

    // Global Option: Font Family
    Chart.defaults.font.family = "'Poppins', sans-serif";
    Chart.defaults.color = '#858796';

    // 1. AREA CHART (Lebih cantik daripada line biasa)
    new Chart(document.getElementById('chartPeminjaman'), {
        type: 'line',
        data: {
            labels: labels.length ? labels : bulanDefault,
            datasets: [
                {
                    label: "Peminjaman",
                    data: peminjamanData,
                    borderColor: "#4e73df",
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    pointBackgroundColor: "#4e73df",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#4e73df",
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                },
                {
                    label: "Pengembalian",
                    data: pengembalianData,
                    borderColor: "#e74a3b", // Warna merah lebih kontras
                    backgroundColor: "rgba(231, 74, 59, 0.05)",
                    pointBackgroundColor: "#e74a3b",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "#e74a3b",
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            plugins: {
                legend: { display: true, position: 'top', align: 'end' },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    titleColor: "#6e707e",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
            },
            scales: {
                x: { grid: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } },
                y: { ticks: { maxTicksLimit: 5, padding: 10 }, grid: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false } }
            }
        }
    });

    // 2. DOUGHNUT CHART (Modern Style)
    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: kategoriValues,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617', '#60616f'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
                borderWidth: 5, // Tebal putih di tengah
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%', // Lubang tengah lebih besar
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });

    // 3. BAR CHART - USER AKTIF
    new Chart(document.getElementById('chartUserAktif'), {
        type: 'bar',
        data: {
            labels: labels.length ? labels : bulanDefault,
            datasets: [{
                label: "User Aktif",
                data: userAktifData,
                backgroundColor: "#1cc88a",
                hoverBackgroundColor: "#17a673",
                borderRadius: 5,
                barPercentage: 0.6,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { borderDash: [2] }, beginAtZero: true }
            }
        }
    });

    // 4. BAR CHART - DISTRIBUSI KELAS
    new Chart(document.getElementById('chartSiswaKelas'), {
        type: 'bar',
        data: {
            labels: kelasLabels,
            datasets: [{
                label: "Jumlah Siswa",
                data: kelasValues,
                backgroundColor: "#36b9cc",
                hoverBackgroundColor: "#2c9faf",
                borderRadius: 5,
                barPercentage: 0.6,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { borderDash: [2] }, beginAtZero: true }
            }
        }
    });
</script>

@endsection