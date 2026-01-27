@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary: #0d3c84;
        --secondary: #1a56b3;
        --accent: #e7a75e;
        --bg-body: #f0f4f8;
        --glass: rgba(255, 255, 255, 0.9);
    }

    body { 
        background-color: var(--bg-body); 
        font-family: 'Plus Jakarta Sans', sans-serif;
        overflow-x: hidden;
    }

    /* === ANIMATED BACKGROUND HERO === */
    .hero-review {
        background: linear-gradient(135deg, #0d3c84, #1a56b3, #061d41);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        color: white;
        text-align: center;
        padding: 120px 20px 180px;
        border-radius: 0 0 100px 100px;
        position: relative;
        overflow: hidden;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .hero-review h1 { 
        font-weight: 800; 
        font-size: 3.5rem; 
        letter-spacing: -1px;
        text-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    .hero-review p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 10px auto 0;
    }

    /* === FLOATING FORM CARD === */
    .form-wrapper {
        max-width: 700px;
        margin: -110px auto 80px;
        padding: 0 25px;
        position: relative;
        z-index: 10;
    }

    .card-write {
        background: var(--glass);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        padding: 45px;
        border-radius: 35px;
        box-shadow: 0 30px 60px rgba(13, 60, 132, 0.15);
        border: 1px solid rgba(255,255,255,0.4);
    }

    .form-label-custom {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border-radius: 16px;
        padding: 15px 20px;
        border: 2px solid #e2e8f0;
        background: rgba(255, 255, 255, 0.8);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 5px rgba(231, 167, 94, 0.15);
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 16px;
        border-radius: 18px;
        width: 100%;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        transition: 0.4s;
        box-shadow: 0 10px 25px rgba(13, 60, 132, 0.3);
        margin-top: 10px;
    }

    .btn-submit:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 15px 30px rgba(13, 60, 132, 0.4);
        filter: brightness(1.1);
    }

    /* === MASONRY-LIKE GRID === */
    .review-grid {
        max-width: 1200px;
        margin: 0 auto 100px;
        padding: 0 25px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 35px;
    }

    .review-card {
        background: white;
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.02);
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .review-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 6px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 30px 30px 0 0;
        opacity: 0;
        transition: 0.3s;
    }

    .review-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.08);
    }

    .review-card:hover::before { opacity: 1; }

    .quote-icon {
        width: 45px;
        height: 45px;
        background: rgba(231, 167, 94, 0.1);
        color: var(--accent);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
    }
    
    .review-text { 
        color: #334155; 
        font-size: 1.1rem;
        line-height: 1.8; 
        margin-bottom: 30px;
        font-weight: 500;
    }

    .user-info { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        padding-top: 25px; 
        border-top: 1px dashed #e2e8f0;
    }

    .avatar-wrapper {
        position: relative;
    }

    .avatar-circle {
        width: 55px;
        height: 55px;
        border-radius: 20px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.3rem;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .status-badge {
        background: #10b981;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        position: absolute;
        bottom: -2px;
        right: -2px;
        border: 3px solid white;
    }

    .user-name { color: #1e293b; font-weight: 700; margin: 0; font-size: 1.05rem; }
    .user-role { color: #64748b; font-size: 0.85rem; font-weight: 500; }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 10px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }
</style>

<section class="hero-review">
    <div class="container">
        <h1>Suara Pembaca</h1>
        <p>Bergabunglah dengan ratusan pembaca lainnya dan bagikan momen inspiratifmu di Perpustakaan SDN 75.</p>
    </div>
</section>

<div class="form-wrapper">
    <div class="card-write">
        <div class="d-flex align-items-center mb-4">
            <div class="quote-icon me-3 mb-0"><i class="bi bi-pencil-square"></i></div>
            <h4 class="fw-bold mb-0" style="color: var(--primary)">Berikan Feedback</h4>
        </div>
        
        <form id="reviewForm">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <label class="form-label-custom">Nama Lengkap</label>
                    <input type="text" id="name" class="form-control" placeholder="E.g. Andi Wijaya" required>
                </div>
                <div class="col-md-6 mb-1">
                    <label class="form-label-custom">Status</label>
                    <input type="text" id="role" class="form-control" placeholder="E.g. Siswa Kelas 6" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label-custom">Pesan Ulasan</label>
                <textarea id="message" class="form-control" rows="4" placeholder="Tuliskan pengalaman berkesanmu di sini..." required></textarea>
            </div>
            <button type="submit" class="btn-submit">
                Kirim Ulasan <i class="bi bi-send-fill ms-2"></i>
            </button>
        </form>
    </div>
</div>

<div class="review-grid" id="reviewGrid">
    </div>

<script>
    const form = document.getElementById('reviewForm');
    const grid = document.getElementById('reviewGrid');

    // Load data
    window.onload = () => {
        const saved = JSON.parse(localStorage.getItem('userReviews')) || [];
        saved.forEach(data => createCard(data));
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const role = document.getElementById('role').value;
        const message = document.getElementById('message').value;
        
        // Premium color palette
        const colors = ['#0d3c84', '#1e293b', '#0f172a', '#334155', '#475569'];
        const randomColor = colors[Math.floor(Math.random() * colors.length)];
        
        const data = {
            name, role, message, 
            initial: name.substring(0, 2).toUpperCase(),
            color: randomColor,
            date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
        };

        const saved = JSON.parse(localStorage.getItem('userReviews')) || [];
        saved.unshift(data);
        localStorage.setItem('userReviews', JSON.stringify(saved));

        createCard(data, true);
        form.reset();

        // Sweet alert-like feel (optional)
        const btn = form.querySelector('.btn-submit');
        btn.innerHTML = 'Berhasil Terkirim! ✨';
        setTimeout(() => {
            btn.innerHTML = 'Kirim Ulasan <i class="bi bi-send-fill ms-2"></i>';
        }, 2000);
    });

    function createCard(data, isNew = false) {
        const card = document.createElement('div');
        card.className = 'review-card';
        card.innerHTML = `
            <div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="quote-icon"><i class="bi bi-quote"></i></div>
                    <small class="text-muted fw-bold" style="font-size: 0.75rem">${data.date || 'Baru saja'}</small>
                </div>
                <p class="review-text">"${data.message}"</p>
            </div>
            <div class="user-info">
                <div class="avatar-wrapper">
                    <div class="avatar-circle" style="background: ${data.color}">${data.initial}</div>
                    <div class="status-badge"></div>
                </div>
                <div>
                    <h6 class="user-name">${data.name}</h6>
                    <span class="user-role">${data.role}</span>
                </div>
            </div>
        `;
        if(isNew) {
            grid.prepend(card);
            card.style.animation = 'fadeIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards';
        } else {
            grid.appendChild(card);
        }
    }
</script>

@include('components.footer')
@endsection