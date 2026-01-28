<nav class="navbar navbar-expand-lg sticky-top main-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="logo-container shadow-sm">
                <img src="{{ asset('unib.jpg') }}" alt="Logo" class="brand-logo">
            </div>
            <div class="brand-text-wrapper ms-3">
                <span class="logo-text-main">DIGITAL LIBRARY</span>
                <span class="logo-text-sub">FAKULTAS TEKNIK UNIB</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-grid-fill text-white fs-2"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('buku.index') ? 'active' : '' }}" href="{{ route('buku.index') }}">Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('auth.artikel') ? 'active' : '' }}" href="{{ route('auth.artikel') }}">Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('auth.review') ? 'active' : '' }}" href="{{ route('auth.review') }}">Review</a>
                </li>
            </ul>

            <div class="auth-section">
                @auth
                <div class="dropdown">
                    <a href="#" class="profile-card dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                        @php
                            $loginAs = session('login_as');
                            $authUser = Auth::user();
                            $user = ($loginAs === 'siswa') ? App\Models\User::find($authUser->id) : 
                                   (($loginAs === 'guru') ? App\Models\Guru::find($authUser->id) : App\Models\Umum::find($authUser->id));
                            $foto = $user->foto ?? null;
                        @endphp
                        <div class="profile-avatar">
                            @if ($foto)
                                <img src="{{ asset('storage/foto/' . $foto) }}?v={{ time() }}" alt="User">
                            @else
                                <i class="bi bi-person-fill text-primary"></i>
                            @endif
                        </div>
                        <div class="profile-info d-none d-xl-block">
                            <span class="profile-name text-truncate text-white">{{ $user->nama ?? 'User' }}</span>
                            <span class="profile-status text-capitalize text-white-50">{{ $loginAs }}</span>
                        </div>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg animate slideIn profile-dropdown-menu">
                        <li><a class="dropdown-item py-2" href="{{ route('card', Auth::user()->id) }}"><i class="bi bi-card-id-face me-2 text-primary"></i>Kartu Anggota</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile', Auth::user()->id) }}"><i class="bi bi-person-bounding-box me-2 text-primary"></i>Profil Saya</a></li>
                        
                        <li class="dropdown-submenu">
                            <a class="dropdown-item py-2 d-flex justify-content-between align-items-center" href="#">
                                <span><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat</span>
                                <i class="bi bi-chevron-right small"></i>
                            </a>
                            <ul class="dropdown-menu shadow-lg border-0 riwayat-submenu">
                                <li><a class="dropdown-item" href="{{ route('auth.borrow-history') }}">Peminjaman</a></li>
                                <li><a class="dropdown-item" href="{{ route('auth.return-history') }}">Pengembalian</a></li>
                            </ul>
                        </li>

                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger fw-bold py-2"><i class="bi bi-power me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

                @guest
                <div class="d-flex gap-2 auth-btns">
                <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 py-2 btn-sm fw-800 custom-btn-login">
                  LOGIN
                </a>
                <a href="{{ route('signin') }}" class="btn btn-white rounded-pill px-4 py-2 btn-sm fw-800 shadow-sm custom-btn-register">
                  DAFTAR
                </a>
              </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
:root {
    --c-navy: #001F54;      /* Biru Gelap UNIB */
    --c-blue: #0A3D91;      /* Biru Terang Medis/Teknik */
    --c-accent: #3A86FF;    /* Biru Cerah untuk hover */
}

.main-navbar {
    background: var(--c-navy) !important;
    padding: 12px 0;
    transition: all 0.3s ease;
    border-bottom: 2px solid rgba(255,255,255,0.1);
}

/* LOGO */
.logo-container {
    width: 60px;
    height: 60px;
    background: white;
    padding: 3px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid var(--c-accent);
}
.brand-logo { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.logo-text-main { color: white; font-weight: 800; font-size: 1.4rem; display: block; letter-spacing: 0.5px; }
.logo-text-sub { color: rgba(255,255,255,0.7); font-size: 0.7rem; font-weight: 600; }

/* NAV LINKS */
.navbar-nav .nav-link {
    color: rgba(255,255,255,0.8) !important;
    font-weight: 600;
    padding: 10px 18px !important;
    border-radius: 12px;
    transition: all 0.2s;
    font-size: 0.95rem;
}
.navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
    color: white !important;
    background: rgba(255,255,255,0.1);
}

/* PROFILE CARD */
.profile-card {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.1);
    padding: 5px 15px 5px 5px;
    border-radius: 50px;
    border: 1px solid rgba(255,255,255,0.15);
    transition: 0.3s;
}
.profile-card:hover {
    background: rgba(255,255,255,0.2);
}
.profile-avatar {
    width: 38px; height: 38px; background: white;
    border-radius: 50%; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.profile-name { margin-left: 10px; font-weight: 700; font-size: 0.85rem; }
.profile-status { margin-left: 10px; font-size: 0.7rem; }

/* DROPDOWN SUBMENU */
.dropdown-item { font-size: 0.9rem; font-weight: 500; transition: 0.2s; }
.dropdown-item:hover {
    background-color: #f0f4f8 !important;
    color: var(--c-navy) !important;
    padding-left: 25px;
}
.dropdown-submenu { position: relative; }
.dropdown-submenu .riwayat-submenu {
    top: 0; right: 100%; margin-right: 5px; display: none;
    border-radius: 12px;
}
.dropdown-submenu:hover .riwayat-submenu { display: block; }

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .logo-container { width: 48px; height: 48px; }
    .logo-text-main { font-size: 1.1rem; }
    .navbar-collapse {
        background: var(--c-blue);
        margin-top: 15px;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .dropdown-submenu .riwayat-submenu {
        position: static; display: block;
        background: rgba(0,0,0,0.1); margin: 5px 0 0 20px; box-shadow: none;
    }
}

/* BUTTONS */
.btn-white { 
    background: white; 
    color: var(--c-navy) !important; 
    border: none; 
}
.btn-white:hover { 
    background: #eef2f7; 
    transform: scale(1.05); 
}
</style>