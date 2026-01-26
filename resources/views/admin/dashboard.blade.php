@extends('admin.layout')
@section('page-title', 'Dashboard')
@section('content')

<style>
    body {
        background: #f5f7fb;
    }

    /* ===== Dashboard Cards ===== */
    .dashboard-card {
        border-radius: 16px;
        padding: 22px;
        background: white;
        border: 0;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.28s;
        position: relative;
        overflow: hidden;
        color: #333;
    }

    .dashboard-card::after {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        top: -50%;
        left: -50%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: 0.5s;
    }

    .dashboard-card:hover::after {
        top: -30%;
        left: -30%;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .dashboard-card h2 {
        font-size: 28px;
        margin-top: 8px;
        margin-bottom: 4px;
    }

    .dashboard-card h6 {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .dashboard-card small {
        font-size: 12px;
        color: #555;
    }

    /* ===== Chart Containers ===== */
    .chart-container {
        background: white;
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.25s;
        position: relative;
    }

    .chart-container:hover {
        box-shadow: 0 10px 26px rgba(0,0,0,0.08);
    }

    #chartKategori {
        max-height: 260px !important;
    }

    h2, h6 {
        font-weight: 600;
    }

    /* ===== Gradient Stat Cards ===== */
    .bg-gradient-blue { background: linear-gradient(135deg, #4e73df, #1cc88a); color: white; }
    .bg-gradient-orange { background: linear-gradient(135deg, #f6c23e, #f7931e); color: white; }
    .bg-gradient-red { background: linear-gradient(135deg, #e74a3b, #f56236); color: white; }
    .bg-gradient-purple { background: linear-gradient(135deg, #8e44ad, #9b59b6); color: white; }

    /* ===== Table Styling ===== */
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 6px 22px rgba(0,0,0,0.06);
        transition: 0.25s;
    }

    .table-container:hover {
        box-shadow: 0 10px 26px rgba(0,0,0,0.08);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }

    table th {
        font-weight: 600;
        color: #555;
    }

</style>

<div class="container">

    <!-- FORM FILTER -->
    <form method="GET" class="d-flex gap-2 mb-4">
        <select name="mode" class="form-select form-select-sm" style="width:150px">
            <option value="hari" {{ request('mode')=='hari' ? 'selected' : '' }}>Per Hari</option>
            <option value="bulan" {{ request('mode')=='bulan' ? 'selected' : '' }}>Per Bulan</option>
            <option value="tahun" {{ request('mode')=='tahun' ? 'selected' : '' }}>Per Tahun</option>
            <option value="range_tahun" {{ request('mode')=='range_tahun' ? 'selected' : '' }}>Rentang Tahun</option>
        </select>

        <select name="kelas" class="form-select form-select-sm" style="width:150px">
            <option value="">-- Semua Kelas --</option>
            @foreach($semuaKelas as $k)
                <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>

        <input type="date" name="start" class="form-control form-control-sm" value="{{ request('start') }}" style="width:160px">
        <input type="date" name="end" class="form-control form-control-sm" value="{{ request('end') }}" style="width:160px">
        <button class="btn btn-primary btn-sm">Filter</button>
    </form>

    <!-- ROW 1 – STATISTIK -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-blue">
                <h6>Total Pengunjung</h6>
                <h2>{{ $totalPengunjung }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-purple">
                <h6>Total User</h6>
                <h2>{{ $totalUser }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-orange">
                <h6>Total Buku</h6>
                <h2>{{ $totalBuku }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-blue">
                <h6>Buku Sedang Dipinjam</h6>
                <h2>{{ $totalPeminjaman }}</h2>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="dashboard-card text-center bg-gradient-orange">
                <h6>Buku Dikembalikan</h6>
                <h2>{{ $totalPengembalian }}</h2>
            </div>
        </div>
    </div>

    <!-- ROW 2 – LINE & BAR CHART -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">📈 Grafik Peminjaman & Pengembalian</h6>
                <canvas id="chartPeminjaman"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">👤 Grafik User Aktif</h6>
                <canvas id="chartUserAktif"></canvas>
            </div>
        </div>
    </div>

    <!-- ROW 3 – PIE CHART -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">🟣 Diagram Kategori Buku</h6>
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <h6 class="text-center mb-2">👥 Distribusi Siswa Per Kelas</h6>
                <canvas id="chartSiswaKelas"></canvas>
            </div>
        </div>
    </div>

    <!-- ROW 4 – TOP 5 TABLES -->
    <!-- ROW 4 – TOP 5 TABLES -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="table-container">
                <h6 class="text-center mb-3">📚 5 Buku Paling Sering Dipinjam</h6>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Jumlah Dipinjam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bukuFavorit as $index => $buku)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $buku->judul_buku }}</td>
                            <td>{{ $buku->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="table-container">
                <h6 class="text-center mb-3">👤 5 User Paling Aktif</h6>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Jumlah Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($userAktif as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($labels);
const peminjamanData = @json(array_values($grafikPeminjaman));
const pengembalianData = @json(array_values($grafikPengembalian));
const userAktifData = @json(array_values($grafikUserAktif));
const kategoriLabels = {!! json_encode($kategoriBuku->keys()) !!};
const kategoriValues = {!! json_encode($kategoriBuku->values()) !!};
const kelasLabels = {!! json_encode($siswaPerKelas->keys()) !!};
const kelasValues = {!! json_encode($siswaPerKelas->values()) !!};

const bulanDefault = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

// ===== 1) LINE CHART PEMINJAMAN & PENGEMBALIAN =====
new Chart(document.getElementById('chartPeminjaman'), {
    type: 'line',
    data: {
        labels: labels.length ? labels : bulanDefault,
        datasets: [
            {
                label: "Peminjaman",
                data: peminjamanData,
                borderColor: "#2d4bf0",
                backgroundColor: "rgba(45,75,240,0.15)",
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: "#2d4bf0"
            },
            {
                label: "Pengembalian",
                data: pengembalianData,
                borderColor: "#f15a29",
                backgroundColor: "rgba(241,90,41,0.15)",
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: "#f15a29"
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: "bottom", labels: { usePointStyle: true } },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            x: { grid: { display: false }, ticks: { color: "#666" } },
            y: { beginAtZero: true, ticks: { color: "#666" } }
        }
    }
});

// ===== 2) BAR CHART USER AKTIF =====
new Chart(document.getElementById('chartUserAktif'), {
    type: 'bar',
    data: {
        labels: labels.length ? labels : bulanDefault,
        datasets: [{
            label: "User Aktif",
            data: userAktifData,
            backgroundColor: "rgba(34,197,94,0.85)",
            borderRadius: 12,
            maxBarThickness: 40
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: "bottom" } },
        scales: {
            x: { grid: { display: false }, ticks: { color: "#444" } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});

// ===== 3) PIE CHART KATEGORI BUKU =====
new Chart(document.getElementById('chartKategori'), {
    type: 'doughnut',
    data: {
        labels: kategoriLabels,
        datasets: [{
            data: kategoriValues,
            backgroundColor: ["#4e73df", "#1cc88a", "#36b9cc", "#f6c23e", "#e74a3b"],
            borderWidth: 0
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// ===== 4) BAR CHART SISWA PER KELAS =====
new Chart(document.getElementById('chartSiswaKelas'), {
    type: 'bar',
    data: {
        labels: kelasLabels,
        datasets: [{
            label: "Jumlah Siswa",
            data: kelasValues,
            backgroundColor: "rgba(156,39,176,0.85)",
            borderRadius: 12,
            maxBarThickness: 50
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: "bottom" } },
        scales: {
            x: { grid: { display: false }, ticks: { color: "#444" } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>

@endsection
