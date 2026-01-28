@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    :root {
        --primary: #0d3c84;
        --secondary: #2563eb;
        --accent: #e7a75e;
        --light: #f8fafc;
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.6);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f0f4f8;
        overflow-x: hidden;
    }

    /* === HEADER PENDEK DENGAN MESH GRADIENT === */
    .hero-section {
        position: relative;
        background: radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                    radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                    radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        background-color: #0d3c84;
        padding: 60px 20px 100px;
        border-radius: 0 0 50px 50px;
        overflow: hidden;
        color: white;
        text-align: center;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: rotateBg 20s linear infinite;
        z-index: 0;
    }

    @keyframes rotateBg { 
        0% { transform: rotate(0deg); } 
        100% { transform: rotate(360deg); } 
    }

    .hero-content { position: relative; z-index: 2; }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 800;
        letter-spacing: -1.5px;
        background: linear-gradient(to right, #ffffff, #93c5fd);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
    }

    /* === FORM CONTAINER === */
    .form-container {
        margin-top: -65px;
        position: relative;
        z-index: 10;
        padding: 0 20px;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        max-width: 800px;
        margin: 0 auto;
    }

    .input-group-text {
        background: transparent;
        border: 2px solid #e2e8f0;
        border-right: none;
        border-radius: 15px 0 0 15px;
        color: var(--primary);
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-left: none;
        border-radius: 0 15px 15px 0;
        padding: 12px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.5);
        transition: all 0.3s;
    }

    .form-control:focus {
        background: white;
        border-color: var(--secondary);
        box-shadow: none;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #0d3c84 0%, #2563eb 100%);
        color: white !important;
        border: none;
        border-radius: 15px;
        padding: 15px;
        font-weight: 700;
        text-transform: uppercase;
        width: 100%;
        transition: 0.4s;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
    }

    /* === REVIEW GRID === */
    .review-grid {
        max-width: 1200px;
        margin: 60px auto 100px;
        padding: 0 25px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
    }

    .review-card {
        background: white;
        border-radius: 30px;
        padding: 35px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.02);
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .review-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.08);
        border-color: var(--secondary);
    }

    .quote-icon {
        width: 45px; height: 45px;
        background: rgba(231, 167, 94, 0.1);
        color: var(--accent);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    
    .review-text { 
        color: #334155; 
        font-size: 1.05rem;
        line-height: 1.7; 
        margin-bottom: 25px;
        font-weight: 500;
    }

    .user-info { 
        display: flex; align-items: center; gap: 15px; 
        padding-top: 25px; border-top: 1px dashed #e2e8f0;
    }

    .avatar-circle {
        width: 55px; height: 55px;
        border-radius: 18px;
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.2rem;
    }

</style>

<div class="hero-section">
    <div class="hero-content container animate__animated animate__fadeIn">
        <h1 class="hero-title">Jendela Aspirasi</h1>
        <p class="hero-subtitle">Bagikan pengalaman serumu di Perpustakaan SDN 75</p>
    </div>
</div>

<div class="form-container animate__animated animate__fadeInUp">
    @auth
        <div class="glass-card">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-pencil-fill"></i>
                </div>
                <h4 class="fw-bold m-0 text-dark">Tulis Ulasan Baru</h4>
            </div>

            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" placeholder="Nama Anda" value="{{ auth()->user()->name }}" readonly style="background: #f1f5f9;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                            <input type="text" name="role" class="form-control" placeholder="Status Anda (E.g. Siswa Kelas 6)" required>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3 mb-4">
                    <div class="input-group">
                        <span class="input-group-text" style="border-radius: 15px 0 0 15px;"><i class="bi bi-chat-quote"></i></span>
                        <textarea name="message" class="form-control" rows="3" placeholder="Apa yang berkesan hari ini?" required style="border-radius: 0 15px 15px 0;"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-gradient">
                    Kirim Ulasan <i class="bi bi-send ms-2"></i>
                </button>
            </form>
        </div>
    @else
        <div class="glass-card text-center py-5">
            <div class="mb-3">
                <i class="bi bi-chat-square-heart text-primary" style="font-size: 3rem; opacity: 0.5;"></i>
            </div>
            <h4 class="fw-bold text-dark">Ingin Memberikan Ulasan?</h4>
            <p class="text-muted mb-4">Silakan masuk ke akun Anda terlebih dahulu untuk berbagi pengalaman di sini.</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('login') }}" class="btn-gradient w-auto px-5">
                    Login Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    @endauth
</div>

<div class="review-grid">
    @php $colors = ['#0d3c84', '#1e293b', '#ea580c', '#334155', '#475569', '#e7a75e']; @endphp

    @foreach($reviews as $index => $r)
    <div class="review-card animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s">
        <div>
            <div class="d-flex justify-content-between align-items-start">
                <div class="quote-icon"><i class="bi bi-quote"></i></div>
                <small class="text-muted fw-bold" style="font-size: 0.75rem">
                    {{ $r->created_at ? $r->created_at->diffForHumans() : 'Baru saja' }}
                </small>
            </div>
            <p class="review-text">"{{ $r->message }}"</p>
        </div>
        <div class="user-info">
            <div class="avatar-circle" style="background: {{ $colors[$index % count($colors)] }}">
                {{ strtoupper(substr($r->name, 0, 2)) }}
            </div>
            <div>
                <h6 class="user-name">{{ $r->name }}</h6>
                <span class="user-role">{{ $r->role }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#0d3c84'
    });
</script>
@endif

@include('components.footer')
@endsection