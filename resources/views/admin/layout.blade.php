<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpustakaan SDN 75 - Admin</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --side-bg: #020617; 
      --accent: #f7931e;  
      --text-muted: #94a3b8;
      --sidebar-width: 280px;
      --sidebar-collapsed-width: 85px;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
      margin: 0;
      overflow-x: hidden;
    }

    /* === SIDEBAR STYLE === */
    .sidebar {
      background-color: var(--side-bg);
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 3000;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 15px rgba(0,0,0,0.3);
    }

    .sidebar .brand {
      padding: 25px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .sidebar .brand img {
      width: 45px;
      height: 45px;
      object-fit: contain;
    }

    .sidebar .brand-text h4 {
      font-size: 14px;
      color: white;
      margin: 0;
      font-weight: 600;
      white-space: nowrap;
    }

    .sidebar ul {
      list-style: none;
      padding: 15px 12px;
      margin: 0;
      flex-grow: 1;
    }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      color: var(--text-muted);
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 12px;
      transition: 0.3s;
      white-space: nowrap;
      margin-bottom: 5px;
      cursor: pointer;
    }

    .sidebar ul li a i { font-size: 1.3rem; min-width: 35px; }

    .sidebar ul li a:hover {
      background: rgba(247, 147, 30, 0.1);
      color: var(--accent);
    }

    .sidebar ul li a.active {
      background: var(--accent);
      color: #000;
      font-weight: 600;
    }

    /* Dropdown Arrow Animation */
    .sidebar ul li a .bi-chevron-down {
      transition: transform 0.3s;
      font-size: 0.8rem;
      min-width: auto;
    }
    .sidebar ul li a[aria-expanded="true"] .bi-chevron-down {
      transform: rotate(180deg);
    }

    /* Submenu Style */
    .submenu {
      list-style: none;
      padding-left: 35px !important;
      margin-bottom: 10px !important;
    }
    .submenu li a {
      padding: 8px 15px !important;
      font-size: 13px !important;
      background: transparent !important;
    }
    .submenu li a:hover {
        color: white !important;
    }

    .sidebar .admin-profile {
      padding: 15px;
      background: rgba(255,255,255,0.03);
      margin: 15px;
      border-radius: 12px;
    }

    .admin-profile .nav-link {
      color: white;
      display: flex;
      align-items: center;
      gap: 10px;
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
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .menu-toggle {
      font-size: 1.6rem;
      cursor: pointer;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
      background: #f0f2f5;
      color: var(--side-bg);
      transition: 0.2s;
    }

    .menu-toggle:hover {
      background: var(--accent);
      color: white;
    }

    /* === RESPONSIVE STATES === */
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
      .sidebar.active { left: 0 !important; }
      .main-content { margin-left: 0 !important; }
      body.sidebar-open { overflow: hidden; }
      .menu-toggle { position: relative; z-index: 4000; }
      .sidebar-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(2px);
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
        <h4>Perpustakaan<br><span style="font-weight: 300; font-size: 11px; color: var(--text-muted)">SDN 75 Kota Bengkulu</span></h4>
      </div>
    </div>

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
           role="button" 
           aria-expanded="{{ request()->routeIs('admin.datauser*') || request()->routeIs('admin.dataabsen*') ? 'true' : 'false' }}">
          <div class="d-flex align-items-center">
            <i class="bi bi-people-fill"></i><span>Data User</span>
          </div>
          <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse {{ request()->routeIs('admin.datauser*') || request()->routeIs('admin.dataabsen*') ? 'show' : '' }}" id="userSubmenu">
          <ul class="submenu">
            <li>
              <a href="{{ route('admin.datauser') }}" class="{{ request()->routeIs('admin.datauser') ? 'text-white fw-bold' : '' }}">
                <i class="bi bi-person-gear me-2"></i>Manajemen Data User
              </a>
            </li>
            <li>
              <a href="{{ route('admin.dataabsen') }}" class="{{ request()->routeIs('admin.dataabsen') ? 'text-white fw-bold' : '' }}">
                <i class="bi bi-calendar-check me-2"></i>Manajemen Data Absen
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li>
        <a href="{{ route('admin.datakoleksi') }}" class="{{ request()->routeIs('admin.datakoleksi*') ? 'active' : '' }}">
          <i class="bi bi-journal-bookmark-fill"></i><span>Koleksi Buku</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.dataartikel') }}" class="{{ request()->routeIs('admin.dataartikel*') ? 'active' : '' }}">
          <i class="bi bi-file-text-fill"></i><span>Manajemen Artikel</span>
        </a>
      </li>

      <li>
        <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="{{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">
          <i class="bi bi-clock-history"></i><span>Riwayat</span>
        </a>
      </li>
    </ul>

    <div class="admin-profile">
      <div class="dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle fs-4"></i>
          <span class="flex-grow-1">Administrator</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark shadow border-0 w-100">
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
      <h5 class="ms-3 mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
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

      if (btnHamburger) {
        btnHamburger.addEventListener('click', function(e) {
          e.stopPropagation();
          if (window.innerWidth <= 768) {
            const opened = sidebar.classList.toggle('active');
            overlay.classList.toggle('show', opened);
            document.body.classList.toggle('sidebar-open', opened);
          } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
          }
        });
      }

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