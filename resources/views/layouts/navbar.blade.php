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
        <li class="nav-item">
          <a class="nav-link fw-bold text-dark" href="{{ route('home') }}">Beranda</a>
        </li>

        <li class="nav-item">
          <a class="nav-link fw-bold text-dark" href="{{ route('about') }}">Tentang</a>
        </li>

        <li class="nav-item">
          <a class="nav-link fw-bold text-dark" href="{{ route('buku.index') }}">Buku</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle fw-bold text-dark" href="#" data-bs-toggle="dropdown">
            Lainnya
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('auth.artikel') }}">Artikel</a></li>
            <li><a class="dropdown-item" href="{{ route('auth.kontak') }}">Kontak</a></li>
          </ul>
        </li>
      </ul>

      <!-- PROFIL USER -->
      @auth
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown">

          @php
            $loginAs = session('login_as');
            $authUser = Auth::user();
            $foto = null;
            $nama = null;
            $user = null;

            // Fetch user dari tabel yang sesuai berdasarkan login type
            if ($loginAs === 'siswa') {
              $user = App\Models\User::find($authUser->id);
            } elseif ($loginAs === 'guru') {
              $user = App\Models\Guru::find($authUser->id);
            } elseif ($loginAs === 'umum') {
              $user = App\Models\Umum::find($authUser->id);
            }

            if ($user) {
              $foto = $user->foto ?? null;
              $nama = $user->nama ?? null;
            }
          @endphp

          @if ($foto && file_exists(storage_path('app/public/foto/'.$foto)))
            <img src="{{ asset('storage/foto/'.$foto) }}" width="40" height="40"
                 class="rounded-circle border border-dark me-2">
          @else
            <i class="bi bi-person-circle fs-3 me-2"></i>
          @endif

          <span class="fw-bold">{{ $nama }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="{{ route('card', Auth::user()->id) }}">Kartu Anggota</a>
          </li>
          <li>
            <a class="dropdown-item" href="{{ route('profile', Auth::user()->id) }}">Profil Saya</a>
          </li>

          <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">Riwayat</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('auth.borrow-history') }}">Peminjaman</a></li>
              <li><a class="dropdown-item" href="{{ route('auth.return-history') }}">Pengembalian</a></li>
            </ul>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button class="dropdown-item text-danger">Logout</button>
            </form>
          </li>
        </ul>
      </div>
      @endauth

      <!-- GUEST -->
      @guest
      <div class="d-flex gap-2">
        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-sm">Login</a>
        <a href="{{ route('signin') }}" class="btn btn-dark btn-sm">Daftar</a>
      </div>
      @endguest

    </div>
  </div>
</nav>

<style>
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    right: 100%;
    left: auto;
    margin-top: -1px;
    display: none;
    position: absolute;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    z-index: 1050;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
.dropdown-submenu > a::after {
    content: " ▸";
    float: left;
    margin-right: 5px;
}
.dropdown-item:hover {
    background-color: #ff9800 !important;
    color: #fff !important;
}
.navbar-nav .nav-link:hover {
    color: #ffffff !important;
    background-color: #ffb74d;
    border-radius: 5px;
    transition: 0.2s ease-in-out;
}
</style>
