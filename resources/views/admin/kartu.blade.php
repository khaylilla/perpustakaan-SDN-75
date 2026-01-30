@extends('admin.layout')

@section('page-title', 'Cetak Kartu Anggota A4')

@section('content')
<style>
    /* Import font yang sama persis */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');

    :root {
        --maroon: #7C170D;
        --navy: #1A2A4E;
        --white: #ffffff;
        --card-width: 9cm;
        --card-height: 5.5cm;
        /* Warna Baru untuk Background Halaman */
        --bg-admin: #f8fafc; 
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-admin); /* Background halaman diubah */
        margin: 0;
    }

    /* --- UPDATE STYLE HEADER (ACTION BAR) --- */
    .action-bar-modern {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .action-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .btn-back-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .btn-back-custom:hover {
        background: #f1f5f9;
        color: var(--navy);
        border-color: var(--navy);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f1f5f9;
        padding: 5px 15px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        margin: 0;
    }

    .form-select-custom {
        background: transparent;
        border: none;
        font-size: 13px;
        font-weight: 600;
        color: var(--navy);
        cursor: pointer;
        outline: none;
    }

    .btn-print-primary {
        background: var(--navy);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(26, 42, 78, 0.3);
    }

    .btn-print-primary:hover {
        background: var(--maroon);
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(124, 23, 13, 0.2);
    }

    /* --- STYLE KARTU TETAP SAMA --- */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, var(--card-width));
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    .member-card {
        position: relative;
        width: var(--card-width);
        height: var(--card-height);
        background-color: var(--white);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        page-break-inside: avoid;
    }

    .card-bg {
        position: absolute;
        inset: 0;
        background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
        z-index: 0;
        opacity: 0.8; 
    }

    .card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.75) 100%);
        z-index: 1;
    }

    .content-layer {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .header {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .logo-box {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 8px;
        padding: 4px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .header-text h1 {
        margin: 0;
        font-size: 14px;
        color: var(--maroon);
        font-weight: 900;
        text-transform: uppercase;
    }

    .header-text p {
        margin: 0;
        font-size: 8px;
        color: var(--navy);
        font-weight: 700;
        letter-spacing: 1px;
    }

    .main-body {
        display: flex;
        padding: 15px 20px;
        gap: 18px;
        flex-grow: 1;
        align-items: center;
    }

    .photo-frame {
        width: 75px;
        height: 100px;
        border-radius: 8px;
        background: #fff;
        padding: 3px;
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }

    .photo-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .info-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .info-item {
        margin-bottom: 2px;
    }

    .label {
        font-size: 7px;
        text-transform: uppercase;
        color: #475569;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 1px;
    }

    .value {
        font-size: 11px;
        font-weight: 800;
        color: var(--navy);
        line-height: 1.2;
        text-transform: uppercase;
        word-wrap: break-word;
    }

    .barcode-section {
        margin-top: 5px;
        background: white;
        padding: 5px 8px;
        border-radius: 6px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #f1f5f9;
        width: fit-content;
    }

    .footer-strip {
        height: 8px;
        background: linear-gradient(90deg, var(--maroon), #4a0d08);
        width: 100%;
    }

    @media print {
        @page { size: A4; margin: 1cm; }
        body { background: none; padding: 0; }
        .no-print { display: none !important; }
        .card-grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px;
            padding: 0;
            margin: 0;
        }
        .member-card {
            box-shadow: none;
            border: 0.1px solid #eee;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<div class="container-fluid p-0">
    {{-- NEW HEADER (ACTION BAR) --}}
    <div class="no-print action-bar-modern">
        <div class="action-left">
            <a href="{{ route('admin.dataabsen') }}" class="btn-back-custom">
                <i class="bi bi-chevron-left"></i> Kembali
            </a>
            <div class="filter-group">
                <label><i class="bi bi-filter-left"></i> Filter Kartu</label>
                <select id="typeFilter" class="form-select-custom" onchange="filterKartu(this.value)">
                    <option value="">Semua Anggota</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
        </div>
        
        <button onclick="window.print()" class="btn-print-primary">
            <i class="bi bi-printer-fill"></i> Cetak A4 Sekarang
        </button>
    </div>

    {{-- GRID KARTU --}}
    <div class="card-grid" id="cardGrid">
        @foreach($anggota as $i => $item)
            <div class="member-card" data-type="{{ $item->type }}">
                <div class="card-bg"></div>
                <div class="card-overlay"></div>

                <div class="content-layer">
                    <div class="header">
                        <div class="logo-box">
                            <img src="{{ asset('unib.jpg') }}" style="width:100%; height:auto;">
                        </div>
                        <div class="header-text">
                            <h1>Perpustakaan SDN 75</h1>
                            <p>KARTU DIGITAL ANGGOTA</p>
                        </div>
                    </div>

                    <div class="main-body">
                        <div class="photo-frame">
                            <img src="{{ asset('storage/foto/'.$item->foto) }}" onerror="this.src='{{ asset('foto profil.png') }}'">
                        </div>

                        <div class="info-container">
                            <div class="info-item">
                                <div class="label">Nama Lengkap</div>
                                <div class="value">{{ $item->nama }}</div>
                            </div>

                            <div class="info-item">
                                <div class="label">
                                    {{ $item->type === 'siswa' ? 'NISN' : ($item->type === 'guru' ? 'NIP' : 'Email') }}
                                </div>
                                <div class="value">{{ $item->identifier }}</div>
                            </div>

                            <div class="barcode-section">
                                <svg id="barcode-{{ $i }}"></svg>
                                <div style="font-size: 6px; font-weight: 800; color: var(--maroon); margin-top: 3px; letter-spacing: 1px;">
                                    VALID MEMBER
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-strip"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    window.onload = function() {
        @foreach($anggota as $i => $item)
            JsBarcode("#barcode-{{ $i }}", "{{ $item->identifier }}", {
                format: "CODE128",
                width: 1.1,
                height: 30,
                displayValue: false,
                margin: 0
            });
        @endforeach
    };

    function filterKartu(type) {
        const cards = document.querySelectorAll('.member-card');
        cards.forEach(card => {
            if (type === '') {
                card.style.display = 'flex';
            } else {
                card.style.display = card.getAttribute('data-type') === type ? 'flex' : 'none';
            }
        });
    }
</script>
@endsection