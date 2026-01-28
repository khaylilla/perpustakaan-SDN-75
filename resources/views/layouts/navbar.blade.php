<nav class="navbar navbar-expand-lg sticky-top main-navbar navbar-solid" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <div class="logo-wrapper">
                <div class="logo-container shadow-sm">
                    <img src="{{ asset('unib.jpg') }}" alt="Logo" class="brand-logo">
                </div>
            </div>
            <div class="brand-text-wrapper ms-3">
                <span class="logo-text-main">DIGITAL <span class="text-accent">LIBRARY</span></span>
                <span class="logo-text-sub">FAKULTAS TEKNIK UNIB</span>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
            <span class="toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2">
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
                    <a href="#" class="profile-trigger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        @php
                            $loginAs = session('login_as');
                            $user = ($loginAs === 'siswa') ? App\Models\User::find(Auth::id()) : 
                                   (($loginAs === 'guru') ? App\Models\Guru::find(Auth::id()) : App\Models\Umum::find(Auth::id()));
                        @endphp
                        <div class="profile-avatar">
                            @if ($user && $user->foto)
                                <img src="{{ asset('storage/foto/' . $user->foto) }}?v={{ time() }}" alt="User">
                            @else
                                <div class="avatar-placeholder">{{ substr($user->nama ?? 'U', 0, 1) }}</div>
                            @endif
                        </div>
                        <i class="bi bi-chevron-down ms-2 text-white-50 small"></i>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown shadow-lg border-0 animate-slide-up">
                        <li class="dropdown-header-box">
                            <span class="d-block fw-bold text-dark text-truncate" style="max-width: 180px;">{{ $user->nama ?? 'User' }}</span>
                            <span class="badge bg-primary-soft text-primary text-uppercase" style="font-size: 0.65rem;">{{ $loginAs }}</span>
                        </li>
                        <li><hr class="dropdown-divider mx-3"></li>
                        
                        <li><a class="dropdown-item py-2" href="{{ route('card', Auth::id()) }}"><i class="bi bi-card-id-face me-2"></i>Kartu Anggota</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('profile', Auth::id()) }}"><i class="bi bi-person-circle me-2"></i>Profil Saya</a></li>
                        
                        <li class="dropdown-category mt-2 px-3">Riwayat Aktivitas</li>
                        <li><a class="dropdown-item py-2 ps-4" href="{{ route('auth.borrow-history') }}"><i class="bi bi-journal-arrow-up me-2"></i>Peminjaman</a></li>
                        <li><a class="dropdown-item py-2 ps-4" href="{{ route('auth.return-history') }}"><i class="bi bi-journal-arrow-down me-2"></i>Pengembalian</a></li>

                        <li><hr class="dropdown-divider mx-3"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-bold py-2 w-100 border-0 bg-transparent text-start">
                                    <i class="bi bi-power me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

                @guest
                <div class="d-flex align-items-center gap-3 auth-btns-container">
                    <a href="{{ route('login') }}" class="btn-custom-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>LOGIN
                    </a>
                    <a href="{{ route('signin') }}" class="btn-custom-register">
                        DAFTAR
                    </a>
                </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
/* --- VARIABEL WARNA --- */
:root {
    --nav-bg: #021f4b; 
    --accent-blue: #3A86FF;
    --text-dim: #94a3b8;
}

/* --- NAVBAR SOLID STYLE --- */
.main-navbar {
    padding: 12px 0;
    background: var(--nav-bg) !important;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    z-index: 1050;
    transition: padding 0.3s ease;
}

.navbar-scrolled {
    padding: 8px 0 !important;
}

/* --- LOGO & BRAND --- */
.logo-container {
    width: 50px; height: 50px; background: white; padding: 3px;
    border-radius: 12px; border: 2px solid var(--accent-blue);
    display: flex; align-items: center; justify-content: center;
}
.brand-logo { width: 100%; height: 100%; object-fit: contain; }
.logo-text-main { color: white; font-weight: 800; font-size: 1.2rem; letter-spacing: 0.5px; }
.text-accent { color: var(--accent-blue); }
.logo-text-sub { color: var(--text-dim); font-size: 0.7rem; font-weight: 600; display: block; }

/* --- MENU NAVIGASI (DENGAN ANIMASI) --- */
.navbar-nav .nav-link {
    color: rgba(255,255,255,0.7) !important;
    font-weight: 500; font-size: 0.95rem;
    padding: 10px 15px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: inline-block;
}

.navbar-nav .nav-link::after {
    content: ''; position: absolute; bottom: 5px; left: 50%; width: 0; height: 2px;
    background: var(--accent-blue); transition: all 0.3s ease; transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after, 
.navbar-nav .nav-link.active::after {
    width: 60%;
}

.navbar-nav .nav-link:hover {
    color: white !important;
    transform: translateY(-2px);
}

.navbar-nav .nav-link:active {
    transform: translateY(1px) scale(0.95);
    transition: all 0.1s;
}

/* --- TOMBOL LOGIN & DAFTAR --- */
.btn-custom-login {
    color: var(--accent-blue) !important;
    border: 2px solid var(--accent-blue);
    padding: 7px 18px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none;
    transition: 0.3s;
}
.btn-custom-login:hover {
    background: var(--accent-blue);
    color: white !important;
}

.btn-custom-register {
    background: var(--accent-blue);
    color: white !important;
    padding: 9px 22px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none;
    transition: 0.3s;
}
.btn-custom-register:hover {
    background: #2563eb;
    transform: translateY(-2px);
}

/* --- REDESAIN DROPDOWN PROFIL --- */
.profile-trigger {
    display: flex; align-items: center; text-decoration: none;
    padding: 4px; border-radius: 50px; transition: 0.3s;
}

.profile-avatar {
    width: 38px; height: 38px; border-radius: 50%; border: 2px solid var(--accent-blue);
    overflow: hidden; background: white; display: flex; align-items: center; justify-content: center;
}
.profile-avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-placeholder { font-weight: 800; color: var(--nav-bg); }

.custom-dropdown {
    min-width: 240px; border-radius: 16px; padding: 12px 0;
    margin-top: 15px !important; background: white;
}

.dropdown-header-box { padding: 8px 20px; }
.bg-primary-soft { background: #eef4ff; padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; }
.dropdown-category { font-size: 0.7rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px; }

.custom-dropdown .dropdown-item {
    color: #495057; font-weight: 500; font-size: 0.9rem; border-radius: 8px;
    margin: 0 10px; width: calc(100% - 20px); transition: 0.2s;
}

.custom-dropdown .dropdown-item i { color: var(--accent-blue); font-size: 1.1rem; }
.custom-dropdown .dropdown-item:hover {
    background: #f8f9fa; color: var(--accent-blue); transform: translateX(5px);
}
.custom-dropdown .dropdown-item.text-danger i { color: #dc3545; }

/* --- RESPONSIVE MOBILE --- */
.toggler-icon { width: 25px; height: 2px; background: white; display: block; margin: 5px 0; }
@media (max-width: 991px) {
    .navbar-collapse {
        background: #0a192f;
        margin-top: 15px; padding: 20px; border-radius: 15px;
    }
    .auth-btns-container { flex-direction: column; width: 100%; margin-top: 15px; }
    .btn-custom-login, .btn-custom-register { width: 100%; text-align: center; }
}

/* ANIMATIONS */
.animate-slide-up { animation: slideUp 0.3s cubic-bezier(0, 0, 0.2, 1); }
@keyframes slideUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('mainNavbar');
        if (window.scrollY > 40) {
            nav.classList.add('navbar-scrolled');
        } else {
            nav.classList.remove('navbar-scrolled');
        }
    });
</script>