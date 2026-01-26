@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

<style>
    :root {
        --navy-bg: #050a18;
        --accent-gold: #f7931e;
        --glass-white: rgba(255, 255, 255, 0.05);
        --glass-border: rgba(255, 255, 255, 0.1);
        --text-slate: #94a3b8;
    }

    body {
        background-color: var(--navy-bg);
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #fff;
    }

    .content-container {
        padding: 80px 20px;
        min-height: 100vh;
        background: radial-gradient(circle at top right, rgba(247, 147, 30, 0.05), transparent 400px),
                    radial-gradient(circle at bottom left, rgba(0, 74, 173, 0.1), transparent 400px);
    }

    /* --- HEADER SECTION --- */
    .page-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .page-header h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 900;
        font-size: clamp(2.5rem, 5vw, 4rem);
        background: linear-gradient(to bottom, #fff 50%, var(--accent-gold));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    /* --- FILTER BAR MODERN --- */
    .filter-wrapper {
        background: var(--glass-white);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 15px 30px;
        margin-bottom: 50px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .filter-group {
        display: flex;
        align-items: center;
        background: rgba(78, 75, 75, 0.2);
        border-radius: 15px;
        padding: 5px 15px;
        flex: 1;
        min-width: 200px;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .filter-group:focus-within {
        border-color: var(--accent-gold);
        box-shadow: 0 0 15px rgba(247, 147, 30, 0.2);
    }

    .filter-group i { color: var(--accent-gold); margin-right: 10px; }

    .filter-group input, .filter-group select {
        background: transparent;
        border: none;
        color: #fff;
        width: 100%;
        padding: 10px 0;
        outline: none;
        font-size: 0.9rem;
    }

    .filter-group select option { background: var(--navy-bg); color: #fff; }

    /* --- BOOK GRID & CARDS --- */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 30px;
    }

    .book-card {
        background: var(--glass-white);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 15px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
    }

    .book-card:hover {
        transform: translateY(-12px);
        background: rgba(255,255,255,0.08);
        border-color: var(--accent-gold);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .book-card .img-wrapper {
        width: 100%;
        height: 300px;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 15px;
        position: relative;
    }

    .book-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .book-card:hover img { transform: scale(1.1); }

    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(247, 147, 30, 0.9);
        color: #000;
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        z-index: 2;
    }

    .book-info h4 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: #fff;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-info p {
        color: var(--text-slate);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 0;
    }

    /* --- PAGINATION --- */
    .custom-pagination {
        margin-top: 60px;
    }

    .pagination .page-link {
        background: var(--glass-white);
        border: 1px solid var(--glass-border);
        color: #fff;
        margin: 0 5px;
        border-radius: 12px !important;
        transition: 0.3s;
    }

    .pagination .page-item.active .page-link {
        background: var(--accent-gold);
        border-color: var(--accent-gold);
        color: #000;
        font-weight: bold;
    }

    .pagination .page-link:hover {
        background: rgba(255,255,255,0.1);
        color: var(--accent-gold);
    }

    @media (max-width: 768px) {
        .filter-wrapper { border-radius: 20px; padding: 20px; }
        .page-header h1 { font-size: 2.5rem; }
    }
</style>

<div class="content-container">
    <div class="container">
        <header class="page-header" data-aos="fade-down">
            <span class="text-uppercase tracking-widest text-warning fw-bold small">Digital Library</span>
            <h1>Koleksi Buku</h1>
            <p class="text-white">Temukan pengetahuan dalam ribuan literatur pilihan.</p>
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
                    <option value="">Kategori</option>
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
                    <p><i class="bi bi-person me-1"></i> {{ $book->penulis ?? 'Anonim' }}</p>
                    <p class="mt-2 text-white-50 small">{{ Str::limit($book->deskripsi, 50) }}</p>
                </div>
            </a>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-book-half fs-1 text-white"></i>
                <p class="mt-3 text-white">Buku tidak ditemukan dalam koleksi kami.</p>
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

    // Smooth Slideshow Logic for Card
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