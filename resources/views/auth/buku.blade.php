@extends('layouts.app')

@section('content')
<style>
  /* 🎨 Layout Umum */
  .content-container {
    padding: 50px 30px;
    background: linear-gradient(135deg, #213044ff, #14234dff);
    min-height: 100vh;
    color: #fff;
    position: relative;
    overflow: hidden;
  }

  .content-container::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.08), transparent 50%),
                radial-gradient(circle at bottom left, rgba(255, 200, 0, 0.12), transparent 60%);
    z-index: 0;
  }

  .content-container > * { position: relative; z-index: 1; }

  h3 {
    text-align: center;
    font-weight: 700;
    color: #ffcc66;
    letter-spacing: 0.5px;
    text-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
  }

  /* 🔍 Filter Bar */
  .filter-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 12px;
    margin: 25px 0 40px;
  }

  .filter-bar input[type="text"],
  .filter-bar select {
    padding: 10px 15px;
    border-radius: 30px;
    border: none;
    outline: none;
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    font-size: 14px;
    backdrop-filter: blur(6px);
    transition: 0.3s ease;
  }

  .filter-bar input[type="text"]:focus,
  .filter-bar select:focus {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 8px rgba(255, 204, 102, 0.6);
  }

  .filter-bar option { color: #000; }

  /* 📚 Container Buku */
  .books-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    padding: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 25px;
    backdrop-filter: blur(8px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    animation: fadeIn 0.8s ease;
  }

  .book-card {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 20px;
    text-align: center;
    padding: 15px;
    transition: all 0.35s ease;
    color: #fff;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    animation: fadeUp 0.6s ease both;
  }

  .book-card:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 10px 25px rgba(255, 204, 102, 0.3);
    background: rgba(255, 255, 255, 0.2);
  }

  .book-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 15px;
    transition: transform 0.4s ease;
  }

  .book-card:hover img { transform: scale(1.05); }

  .book-title { margin-top: 10px; font-size: 15px; font-weight: 600; color: #ffcc66; }
  .book-jenis { font-size: 13px; color: #d0d0d0; }
  .book-desc { font-size: 12px; color: #eee; margin-top: 6px; }

  .pagination { display: flex; justify-content: center; margin-top: 30px; }
  .pagination .page-item { margin: 0 4px; }
  .pagination .page-link {
    border-radius: 10px;
    padding: 8px 14px;
    color: #004aad;
    background: #fff;
    border: none;
    font-weight: 600;
    transition: all 0.3s;
  }
  .pagination .page-link:hover { background: #ffcc66; color: #001f4d; }
  .pagination .active .page-link { background: #ffcc66; color: #001f4d; }

  @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes fadeUp { 0% { opacity: 0; transform: translateY(25px); } 100% { opacity: 1; transform: translateY(0); } }

  @media (max-width: 768px) {
    .books-container { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    .filter-bar { flex-direction: column; gap: 10px; }
  }
</style>

<div class="content-container">
  <h3 class="text-center fw-bold mb-4">Daftar Buku - {{ $kategori ?? 'Semua Buku' }}</h3>

  <!-- 🔍 Filter Bar -->
  <div class="filter-bar">
    <input type="text" id="search" placeholder="🔍 Cari buku..." value="{{ request('search') }}" onkeypress="if(event.key==='Enter') applyFilters()">
    
    <select id="searchType" onchange="updatePlaceholder(); applyFilters()">
      <option value="all" {{ request('searchType')=='all'?'selected':'' }}>Cari Berdasarkan</option>
      <option value="judul" {{ request('searchType')=='judul'?'selected':'' }}>Judul</option>
      <option value="penulis" {{ request('searchType')=='penulis'?'selected':'' }}>Penulis</option>
      <option value="penerbit" {{ request('searchType')=='penerbit'?'selected':'' }}>Penerbit</option>
      <option value="tahun_terbit" {{ request('searchType')=='tahun_terbit'?'selected':'' }}>Tahun Terbit</option>
    </select>

    <select id="urutkan" onchange="applyFilters()">
        <option value="">Urutkan</option>
        <option value="judul_az" {{ request('sort')=='judul_az'?'selected':'' }}>Judul A-Z</option>
        <option value="judul_za" {{ request('sort')=='judul_za'?'selected':'' }}>Judul Z-A</option>
        <option value="terlama" {{ request('sort')=='terlama'?'selected':'' }}>Tahun Terlama</option>
        <option value="terbaru" {{ request('sort')=='terbaru'?'selected':'' }}>Tahun Terbaru</option>
    </select>

   <select id="filterKategori" onchange="applyFilters()">
      <option value="">Kategori</option>
      <option value="bacaan" {{ request('kategori')=='bacaan'?'selected':'' }}>Bacaan</option>
      <option value="referensi" {{ request('kategori')=='referensi'?'selected':'' }}>Referensi</option>
      <option value="skripsi" {{ request('kategori')=='skripsi'?'selected':'' }}>Skripsi</option>
    </select>

  </div>

  <!-- 📚 Daftar Buku -->
  <div class="books-container" id="book-list">
    @forelse($books as $book)
      <div class="book-card" style="cursor:pointer" onclick="window.location='{{ route('buku.show', $book->id) }}'">
        @php $covers = json_decode($book->cover,true); @endphp
        @if($covers && count($covers)>0)
          <img src="{{ asset('storage/'.$covers[0]) }}" data-covers='@json($covers)' alt="{{ $book->judul }}" class="book-cover">
        @else
          <img src="{{ asset('images/no-image.png') }}" alt="Tidak ada cover" class="book-cover">
        @endif
        <div class="book-title">{{ $book->judul }}</div>
        <div class="book-jenis">{{ $book->kategori }}</div>
        <div class="book-desc">{{ Str::limit($book->deskripsi, 60) }}</div>
      </div>
    @empty
      <p class="text-center text-light fw-bold">Belum ada data buku 📚</p>
    @endforelse
  </div>

  <div class="d-flex justify-content-center mt-4">
    {{ $books->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
</div>

@include('components.footer')

<script>
  function updatePlaceholder() {
    const searchType = document.getElementById('searchType').value;
    const search = document.getElementById('search');
    switch(searchType) {
        case 'judul': search.placeholder = "🔍 Cari Judul..."; break;
        case 'penulis': search.placeholder = "🔍 Cari Penulis..."; break;
        case 'penerbit': search.placeholder = "🔍 Cari Penerbit..."; break;
        case 'tahun_terbit': search.placeholder = "🔍 Cari Tahun Terbit..."; break;
        default: search.placeholder = "🔍 Cari buku..."; break;
    }
  }

  function applyFilters() {
    const search = document.getElementById('search').value.trim();
    const sort = document.getElementById('urutkan').value;
    const searchType = document.getElementById('searchType').value;
    const kategori = document.getElementById('filterKategori').value;

    const params = new URLSearchParams(window.location.search);

    // hapus page kalau ganti filter
    params.delete('page');

    if (search) params.set('search', search);
    else params.delete('search');

    if (sort) params.set('sort', sort);
    else params.delete('sort');

    if (searchType) params.set('searchType', searchType);
    else params.delete('searchType');

    if (kategori) params.set('kategori', kategori);
    else params.delete('kategori');

    window.location.href = `/buku?${params.toString()}`;
}

  document.addEventListener('DOMContentLoaded', function() {
    updatePlaceholder();

    const covers = document.querySelectorAll('.book-cover');
    covers.forEach(img => {
      const coverList = JSON.parse(img.dataset.covers || "[]");
      if(coverList.length > 1){
        let current = 0;
        setInterval(()=>{current=(current+1)%coverList.length; img.src='/storage/'+coverList[current];},3000);
      }
    });
  });
</script>
@endsection
