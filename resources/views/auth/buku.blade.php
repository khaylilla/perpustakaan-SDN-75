@extends('layouts.app')

@section('title', 'Koleksi Buku')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
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

    .content-container {
        padding: 40px 0 80px;
        min-height: 100vh;
    }

    /* --- HEADER SECTION --- */
    .page-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .page-header h1 {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        background: linear-gradient(135deg, var(--deep-navy) 30%, var(--primary-blue) 60%, var(--accent-red) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    /* --- FILTER BAR MODERN (Glassmorphism Light) --- */
    .filter-wrapper {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(2, 31, 75, 0.1);
        border-radius: 30px;
        padding: 15px 30px;
        margin-bottom: 50px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        box-shadow: 0 15px 35px rgba(2, 31, 75, 0.05);
    }

    .filter-group {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border-radius: 15px;
        padding: 5px 15px;
        flex: 1;
        min-width: 200px;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }

    .filter-group:focus-within {
        border-color: var(--primary-blue);
        background: #fff;
        box-shadow: 0 0 15px rgba(10, 88, 202, 0.1);
    }

    .filter-group i { color: var(--primary-blue); margin-right: 10px; }

    .filter-group input, .filter-group select {
        background: transparent;
        border: none;
        color: var(--text-main);
        width: 100%;
        padding: 10px 0;
        outline: none;
        font-size: 0.9rem;
    }

    /* --- BOOK GRID & CARDS --- */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 30px;
    }

    .book-card {
        background: var(--pure-white);
        border: 1px solid #f1f5f9;
        border-radius: 28px;
        padding: 18px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 4px 6px rgba(2, 31, 75, 0.02);
    }

    .book-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(2, 31, 75, 0.1);
        border-color: var(--primary-blue);
    }

    .book-card .img-wrapper {
        width: 100%;
        height: 320px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 15px;
        position: relative;
        background: #f8fafc;
    }

    .book-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.4s ease, transform 0.6s ease;
    }

    .category-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary-blue);
        color: #fff;
        padding: 5px 14px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(10, 88, 202, 0.3);
    }

    .book-info h4 {
        font-family: var(--font-heading);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--deep-navy);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-info p {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--primary-blue) !important;
        font-weight: 600;
    }

    /* --- PAGINATION --- */
    .custom-pagination { margin-top: 60px; }
    .pagination .page-link {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: var(--deep-navy);
        margin: 0 4px;
        border-radius: 12px !important;
        padding: 10px 18px;
    }
    .pagination .page-item.active .page-link {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
        color: #fff;
    }

    @media (max-width: 768px) {
        .filter-wrapper { border-radius: 20px; padding: 20px; }
    }
</style>

<div class="bg-animated">
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
</div>

<div class="content-container">
    <div class="container">
        <header class="page-header" data-aos="fade-down">
            <span class="text-uppercase tracking-widest text-primary fw-bold small">Digital Repository</span>
            <h1>Koleksi Buku</h1>
            <p class="text-muted">Temukan pengetahuan dalam ribuan literatur pilihan fakultas.</p>
        </header>

        <div class="filter-wrapper" data-aos="fade-up">
            <div class="filter-group">
                <i class="bi bi-search"></i>
                <input type="text" id="search" placeholder="Cari judul, penulis..." value="{{ request('search') }}" onkeypress="if(event.key==='Enter') applyFilters()">
            </div>

            <div class="filter-group">
                <i class="bi bi-filter"></i>
                <select id="searchType" onchange="applyFilters()">
                    <option value="all" {{ request('searchType')=='all'?'selected':'' }}>Semua Bidang</option>
                    <option value="judul" {{ request('searchType')=='judul'?'selected':'' }}>Judul</option>
                    <option value="penulis" {{ request('searchType')=='penulis'?'selected':'' }}>Penulis</option>
                    <option value="penerbit" {{ request('searchType')=='penerbit'?'selected':'' }}>Penerbit</option>
                </select>
            </div>

            <div class="filter-group">
                <i class="bi bi-sort-down"></i>
                <select id="urutkan" onchange="applyFilters()">
                    <option value="">Urutkan</option>
                    <option value="judul_az" {{ request('sort')=='judul_az'?'selected':'' }}>A - Z</option>
                    <option value="judul_za" {{ request('sort')=='judul_za'?'selected':'' }}>Z - A</option>
                    <option value="terbaru" {{ request('sort')=='terbaru'?'selected':'' }}>Terbaru</option>
                </select>
            </div>

            <div class="filter-group">
                <i class="bi bi-tags"></i>
                <select id="filterKategori" onchange="applyFilters()">
                    <option value="">Semua Kategori</option>
                    <option value="bacaan" {{ request('kategori')=='bacaan'?'selected':'' }}>Bacaan</option>
                    <option value="referensi" {{ request('kategori')=='referensi'?'selected':'' }}>Referensi</option>
                    <option value="skripsi" {{ request('kategori')=='skripsi'?'selected':'' }}>Skripsi</option>
                </select>
            </div>
        </div>

        <div class="books-grid" id="book-list">
            @forelse($books as $book)
            <a href="{{ route('buku.show', $book->id) }}" class="book-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                <span class="category-badge">{{ $book->kategori }}</span>
                <div class="img-wrapper">
                    @php $covers = json_decode($book->cover,true); @endphp
                    @if($covers && count($covers)>0)
                        <img src="{{ asset('storage/'.$covers[0]) }}" data-covers='@json($covers)' alt="{{ $book->judul }}" class="book-cover">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="No Cover" class="book-cover">
                    @endif
                </div>
                <div class="book-info">
                    <h4>{{ $book->judul }}</h4>
                    <p class="author-info"><i class="bi bi-person-fill"></i> {{ $book->penulis ?? 'Anonim' }}</p>
                    <p class="mt-2 small text-muted">{{ Str::limit($book->deskripsi, 65) }}</p>
                </div>
            </a>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-book-half display-1 text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted">Buku tidak ditemukan</h4>
                <p class="text-muted small">Coba kata kunci lain atau reset filter.</p>
            </div>
            @endforelse
        </div>

        <div class="custom-pagination d-flex justify-content-center">
            {{ $books->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@include('components.footer')

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });

    // FUNGSI FILTER ASLI (TIDAK DIHAPUS)
    function applyFilters() {
        const search = document.getElementById('search').value.trim();
        const sort = document.getElementById('urutkan').value;
        const searchType = document.getElementById('searchType').value;
        const kategori = document.getElementById('filterKategori').value;

        const params = new URLSearchParams(window.location.search);
        params.delete('page');

        if (search) params.set('search', search); else params.delete('search');
        if (sort) params.set('sort', sort); else params.delete('sort');
        if (searchType) params.set('searchType', searchType); else params.delete('searchType');
        if (kategori) params.set('kategori', kategori); else params.delete('kategori');

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // LOGIKA SLIDESHOW COVER ASLI (TIDAK DIHAPUS)
    document.addEventListener('DOMContentLoaded', function() {
        const covers = document.querySelectorAll('.book-cover');
        covers.forEach(img => {
            const coverList = JSON.parse(img.dataset.covers || "[]");
            if(coverList.length > 1){
                let current = 0;
                setInterval(() => {
                    img.style.opacity = '0';
                    setTimeout(() => {
                        current = (current + 1) % coverList.length;
                        img.src = '/storage/' + coverList[current];
                        img.style.opacity = '1';
                    }, 400);
                }, 4000);
            }
        });
    });
</script>
@endsection