<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan SDN 75 - Admin</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-blue: #0A58CA;      
      --deep-blue: #021f4b;        
      --accent-red: #d90429;       
      --light-gray: #f8fafc;       
      --text-muted: #94a3b8;
      --sidebar-width: 280px;
      --sidebar-collapsed-width: 85px;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: var(--light-gray);
      margin: 0;
      overflow-x: hidden;
    }

    /* === SIDEBAR STYLE === */
    .sidebar {
      background-color: white;
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 3000;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.05);
      border-right: 1px solid rgba(0,0,0,0.05);
    }

    .sidebar .brand {
      padding: 25px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      border-bottom: 2px solid var(--light-gray);
      flex-shrink: 0; /* Agar header brand tidak mengecil saat scroll */
    }

    .sidebar .brand img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    /* === WRAPPER MENU (Bisa di-scroll) === */
    .sidebar-menu-wrapper {
      flex-grow: 1;
      overflow-y: auto; /* Aktifkan scroll vertikal jika menu kepanjangan */
      overflow-x: hidden;
      padding: 15px 0;
    }

    /* Custom Scrollbar agar lebih tipis & cantik */
    .sidebar-menu-wrapper::-webkit-scrollbar {
      width: 5px;
    }
    .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
      background: #e2e8f0;
      border-radius: 10px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0 15px;
      margin: 0;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      color: #64748b;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 12px;
      transition: 0.3s;
      white-space: nowrap;
      margin-bottom: 4px;
      font-size: 14px;
      font-weight: 500;
    }

    .sidebar ul li a i:first-child { 
      font-size: 1.2rem; 
      min-width: 35px; 
      color: var(--primary-blue);
    }

    .sidebar ul li a:hover {
      background: rgba(10, 88, 202, 0.05);
      color: var(--primary-blue);
    }

    .sidebar ul li a.active {
      background: var(--primary-blue);
      color: white;
      box-shadow: 0 4px 12px rgba(10, 88, 202, 0.2);
    }

    .sidebar ul li a.active i:first-child {
      color: white;
    }

    /* === SUBMENU ADJUSTMENT === */
    .submenu {
      list-style: none;
      padding: 5px 0 5px 40px !important; /* Jarak kiri disesuaikan dengan posisi icon utama */
      margin: 0 !important;
    }
    .submenu li a {
      padding: 8px 15px !important;
      font-size: 13.5px !important;
      background: transparent !important;
      color: #64748b !important;
      margin-bottom: 2px;
      display: flex;
      align-items: center;
    }
    .submenu li a i {
      font-size: 1rem !important;
      min-width: 25px !important;
      color: #94a3b8 !important;
    }
    .submenu li a:hover {
      color: var(--accent-red) !important;
    }
    .submenu li a.active-sub {
      color: var(--primary-blue) !important;
      font-weight: 700;
    }
    .submenu li a.active-sub i {
      color: var(--primary-blue) !important;
    }

    /* === PROFILE / LOGOUT SECTION === */
    .sidebar .admin-profile {
      padding: 15px;
      background: var(--light-gray);
      margin: 10px 15px 20px 15px;
      border-radius: 12px;
      border: 1px solid rgba(0,0,0,0.03);
      flex-shrink: 0; /* Pastikan bagian ini tidak hancur saat scroll */
    }

    .admin-profile .nav-link {
      color: var(--deep-blue);
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }

    /* === TOPBAR & CONTENT === */
    .main-content {
      margin-left: var(--sidebar-width);
      transition: all 0.3s ease;
      min-height: 100vh;
    }

    .topbar {
      background: white;
      padding: 15px 25px;
      display: flex;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000; 
      box-shadow: 0 2px 10px rgba(0,0,0,0.02);
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .menu-toggle {
      font-size: 1.4rem;
      cursor: pointer;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      background: var(--light-gray);
      color: var(--primary-blue);
      transition: 0.2s;
    }

    .menu-toggle:hover {
      background: var(--accent-red);
      color: white;
    }

    @media (min-width: 769px) {
      .sidebar.collapsed { width: var(--sidebar-collapsed-width); }
      .sidebar.collapsed .brand-text,
      .sidebar.collapsed ul li a span,
      .sidebar.collapsed ul li a .bi-chevron-down,
      .sidebar.collapsed .submenu,
      .sidebar.collapsed .admin-profile span,
      .sidebar.collapsed .admin-profile i:last-child { 
        display: none; 
      }
      .main-content.expanded { margin-left: var(--sidebar-collapsed-width); }
    }

    @media (max-width: 768px) {
      .sidebar { left: calc(-1 * var(--sidebar-width)); }
      .sidebar.active { 
        left: 0 !important; 
        box-shadow: 10px 0 30px rgba(0,0,0,0.2);
      }
      .main-content { margin-left: 0 !important; }
      body.sidebar-open { overflow: hidden; }
      .sidebar-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(2, 31, 75, 0.4);
        backdrop-filter: blur(4px);
        z-index: 2500;
        display: none;
      }
      .sidebar-overlay.show { display: block; }
    }
  </style>
</head>
<body>

  <div class="sidebar-overlay" id="overlay"></div>

  <div class="sidebar" id="sidebar">
    <div class="brand">
      <img src="{{ asset('unib.jpg') }}" alt="Logo">
      <div class="brand-text">
        <h4>Perpustakaan<br><span style="font-weight: 400; font-size: 11px; color: var(--accent-red)">SDN 75 Kota Bengkulu</span></h4>
      </div>
    </div>

    <div class="sidebar-menu-wrapper">
      <ul>
        <li>
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i><span>Dashboard</span>
          </a>
        </li>

        <li>
          <a class="d-flex justify-content-between {{ request()->routeIs('admin.datauser*') || request()->routeIs('admin.dataabsen*') ? 'active' : '' }}" 
             data-bs-toggle="collapse" 
             href="#userSubmenu" 
             role="button">
            <div class="d-flex align-items-center">
              <i class="bi bi-people-fill"></i><span>Data User</span>
            </div>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <div class="collapse {{ request()->routeIs('admin.datauser*') || request()->routeIs('admin.dataabsen*') ? 'show' : '' }}" id="userSubmenu">
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.datauser') }}" class="{{ request()->routeIs('admin.datauser') ? 'active-sub' : '' }}">
                  <i class="bi bi-person-gear"></i>Manajemen User
                </a>
              </li>
              <li>
                <a href="{{ route('admin.dataabsen') }}" class="{{ request()->routeIs('admin.dataabsen') ? 'active-sub' : '' }}">
                  <i class="bi bi-calendar2-check"></i>Data Absensi
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li>
          <a href="{{ route('admin.datakoleksi') }}" class="{{ request()->routeIs('admin.datakoleksi*') ? 'active' : '' }}">
            <i class="bi bi-book-half"></i><span>Koleksi Buku</span>
          </a>
        </li>

        <li>
          <a href="{{ route('admin.dataartikel') }}" class="{{ request()->routeIs('admin.dataartikel*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i><span>Manajemen Artikel</span>
          </a>
        </li>

        <li>
          <a class="d-flex justify-content-between {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}" 
             data-bs-toggle="collapse" 
             href="#riwayatSubmenu" 
             role="button">
            <div class="d-flex align-items-center">
              <i class="bi bi-clock-history"></i><span>Riwayat</span>
            </div>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <div class="collapse {{ request()->routeIs('admin.riwayat*') ? 'show' : '' }}" id="riwayatSubmenu">
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="{{ request()->routeIs('admin.riwayat.peminjaman.peminjaman') ? 'active-sub' : '' }}">
                  <i class="bi bi-arrow-up-right-circle"></i>Peminjaman
                </a>
              </li>
              <li>
                <a href="{{ route('admin.riwayat.pengembalian.pengembalian') }}" class="{{ request()->routeIs('admin.riwayat.pengembalian.pengembalian') ? 'active-sub' : '' }}">
                  <i class="bi bi-arrow-down-left-circle"></i>Pengembalian
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>

    <div class="admin-profile">
      <div class="dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle fs-4 text-primary"></i>
          <span class="flex-grow-1 ms-2">Admin</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="main-content" id="main-content">
    <div class="topbar">
      <div class="menu-toggle" id="btn-hamburger">
        <i class="bi bi-list"></i>
      </div>
      <h5 class="ms-3 mb-0 fw-bold text-dark">@yield('page-title', 'Dashboard')</h5>
    </div>

    <div class="p-4">
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');
      const overlay = document.getElementById('overlay');
      const btnHamburger = document.getElementById('btn-hamburger');

      btnHamburger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (window.innerWidth <= 768) {
          sidebar.classList.toggle('active');
          overlay.classList.toggle('show');
          document.body.classList.toggle('sidebar-open');
        } else {
          sidebar.classList.toggle('collapsed');
          mainContent.classList.toggle('expanded');
        }
      });

      overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('show');
        document.body.classList.remove('sidebar-open');
      });

      window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
          sidebar.classList.remove('active');
          overlay.classList.remove('show');
          document.body.classList.remove('sidebar-open');
        }
      });
    });
  </script>
</body>
</html>