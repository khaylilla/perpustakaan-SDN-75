<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #ff9800;">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('unib.jpg') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <!-- Menu kiri -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link fw-bold text-dark" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item"><a class="nav-link fw-bold text-dark" href="{{ route('about') }}">Tentang</a></li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="bukuDropdown" role="button" data-bs-toggle="dropdown">Buku</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Bacaan') }}">Bacaan</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Skripsi') }}">Skripsi</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.kategori', 'Referensi') }}">Referensi</a></li>
            <li><a class="dropdown-item" href="{{ route('buku.index') }}">Semua</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" id="lainnyaDropdown" role="button" data-bs-toggle="dropdown">Lainnya</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('auth.artikel') }}">Artikel</a></li>
            <li><a class="dropdown-item" href="{{ route('auth.kontak') }}">Kontak</a></li>

            <!-- 🔽 Submenu Riwayat -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Riwayat</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Peminjaman</a></li>
                <li><a class="dropdown-item" href="#">Pengembalian</a></li>
                <li><a class="dropdown-item" href="#">Denda</a></li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>

      <!-- Profil kanan -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          @php
              $foto = Auth::user()->foto ?? null;
          @endphp
          @if ($foto && file_exists(storage_path('app/public/foto/'.$foto)))
              <img src="{{ asset('storage/foto/'.$foto) }}" alt="Foto Profil" width="40" height="40" class="rounded-circle border border-dark me-2">
          @else
              <i class="bi bi-person-circle fs-3 me-2"></i>
          @endif
          <span class="fw-bold">{{ Auth::user()->nama ?? 'Pengguna' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="{{ route('absen', Auth::user()->id) }}">Absensi Pengunjung</a></li>
          <li><a class="dropdown-item" href="{{ route('card', Auth::user()->id) }}">Kartu Anggota</a></li>
          <li><a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}">Profil Saya</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<!-- 🌟 CSS untuk Submenu Dropdown -->
<style>
  /* Submenu level 2 */
  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
    display: none;
    position: absolute;
  }

  /* Muncul saat hover (desktop) */
  .dropdown-submenu:hover > .dropdown-menu {
    display: block;
  }

  /* Tampilan rapi */
  .dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }

  /* Cursor lebih enak */
  .dropdown-submenu > a::after {
    content: " ▸";
    float: right;
  }

  /* 🎨 Efek hover di item dropdown */
  .dropdown-item:hover,
  .dropdown-item:focus {
    background-color: #ff9800 !important;
    color: #fff !important;
  }

  /* Supaya teks default tetap gelap */
  .dropdown-item {
    color: #212529 !important;
    transition: all 0.2s ease-in-out;
  }
</style>

<!-- 📱 JS agar submenu bisa diklik di HP -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (el) {
      el.addEventListener('click', function (e) {
        if (window.innerWidth < 992) { // aktif hanya di mobile
          e.preventDefault();
          const nextMenu = this.nextElementSibling;
          if (nextMenu && nextMenu.classList.contains('dropdown-menu')) {
            nextMenu.classList.toggle('show');
          }
        }
      });
    });

    // Tutup submenu kalau dropdown utama ditutup
    document.querySelectorAll('.dropdown').forEach(function (dd) {
      dd.addEventListener('hidden.bs.dropdown', function () {
        this.querySelectorAll('.dropdown-menu.show').forEach(function (menu) {
          menu.classList.remove('show');
        });
      });
    });
  });
</script>
