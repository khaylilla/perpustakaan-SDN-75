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
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
    }

    /* Grid untuk tampilan layar Admin */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, var(--card-width));
        gap: 20px;
        justify-content: center;
        padding: 20px;
    }

    /* Container Kartu Utama */
    .member-card {
        position: relative;
        width: var(--card-width);
        height: var(--card-height);
        background-color: var(--white);
        border-radius: 12px; /* Disesuaikan untuk ukuran kecil */
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        page-break-inside: avoid;
    }

    /* Background & Overlay identik dengan kode Digital */
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

    /* Header identik */
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

    /* Main Body menggunakan Flexbox (Bukan Grid Kaku) */
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

    /* Baris Informasi */
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

    /* Barcode Section */
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

    /* Pengaturan Cetak A4 */
    @media print {
        @page { size: A4; margin: 1cm; }
        body { background: none; padding: 0; }
        .no-print { display: none !important; }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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

<div class="container-fluid py-4">
    {{-- ACTION BAR --}}
    <div class="no-print d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.dataabsen') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="d-flex align-items-center gap-2">
                <label class="fw-semibold small mb-0">Filter:</label>
                <select id="typeFilter" class="form-select form-select-sm" style="width: 130px;" onchange="filterKartu(this.value)">
                    <option value="">-- Semua --</option>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-dark px-4 fw-bold">
            <i class="bi bi-printer me-2"></i> Cetak A4
        </button>
    </div>

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

{{-- Script Barcode menggunakan JsBarcode agar hasil cetak tajam --}}
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