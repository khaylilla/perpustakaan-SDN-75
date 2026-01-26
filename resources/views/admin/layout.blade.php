<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan SDN 75 - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fc;
      margin: 0;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      background: linear-gradient(180deg, #4a4ca4, #575b8d);
      width: 250px;
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed {
      width: 0; 
      overflow: hidden; 
    }

    .sidebar.collapsed .brand,
    .sidebar.collapsed ul,
    .sidebar.collapsed .admin-info {
      display: none;
    }

    .sidebar .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 20px;
    }

    .sidebar .brand img {
      width: 45px;
      height: 45px;
    }

    .sidebar .brand h4 {
      font-size: 15px;
      line-height: 1.2;
      margin: 0;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }

    .sidebar ul li a {
      display: block;
      color: #fff;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 10px;
      transition: 0.3s;
    }

    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background-color: #f7931e;
      color: #222;
      font-weight: 600;
    }

    /* Admin info dropdown */
    .sidebar .admin-info {
      position: absolute;
      bottom: 30px;
      left: 20px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      width: calc(100% - 40px);
      transition: background 0.3s;
    }

    .sidebar .admin-info:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .sidebar .admin-info a.dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      color: #fff;
      text-decoration: none;
      width: 100%;
    }

    /* Dropdown items hover untuk Profile & Logout */
    .sidebar .admin-info .dropdown-menu a,
    .sidebar .admin-info .dropdown-menu button.dropdown-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 16px;
      color: #222;
      text-decoration: none;
      background-color: transparent;
      border: none;
      width: 100%;
      transition: 0.3s;
      cursor: pointer;
    }

    .sidebar .admin-info .dropdown-menu a:hover,
    .sidebar .admin-info .dropdown-menu button.dropdown-item:hover {
      background-color: #f7931e;
      color: #222;
      font-weight: 600;
    }

    .sidebar.collapsed .admin-info span {
      display: none;
    }

    /* Konten utama */
    .main-content {
      margin-left: 250px;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed ~ .main-content {
      margin-left: 0;
    }

    /* Navbar atas */
    .topbar {
      background-color: white;
      border-bottom: 2px solid #f7931e;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 25px;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .menu-toggle {
      font-size: 24px;
      cursor: pointer;
      color: #4a4ca4;
    }

    .topbar .title {
      font-weight: 600;
      font-size: 18px;
      color: #333;
      margin-left: 10px;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        z-index: 2000;
        left: -250px;
      }
      .sidebar.active {
        left: 0;
      }
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<script src="https://unpkg.com/@ericblade/quagga2/dist/quagga.min.js"></script>

<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div>
      <div class="brand">
        <img src="{{ asset('unib.jpg') }}" alt="Logo UNIB">
        <h4>
          <span style="font-size:14px;">Perpustakaan</span><br>
          <span style="font-weight:400;">Sekolah Dasar Negeri 75</span>
        </h4>
      </div>

      <ul>
        <li><a href="{{ route('admin.dashboard') }}" ><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
        <li><a href="{{ route('admin.datauser') }}"><i class="bi bi-people me-2"></i>Manajemen Data User</a></li>
        <li><a href="{{ route('admin.datakoleksi') }}"><i class="bi bi-book me-2"></i>Manajemen Koleksi</a></li>
        <li><a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}"><i class="bi bi-journal-text me-2"></i>Manajemen Riwayat</a></li>
      </ul>
    </div>

    <!-- Admin info dropdown -->
    <div class="admin-info dropdown">
      <a href="#" class="d-flex align-items-center dropdown-toggle" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle fs-4"></i>
        <span>Admin</span>
      </a>
      <ul class="dropdown-menu">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center">
        <i class="bi bi-list menu-toggle" id="menu-toggle"></i>
        <span class="title">@yield('page-title')</span>
      </div>
    </div>

    <div class="p-4">
      @yield('content')
    </div>
  </div>

  <script>
    const toggleBtn = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
