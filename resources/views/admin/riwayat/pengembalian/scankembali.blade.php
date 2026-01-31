@extends('admin.layout')

@section('page-title', 'Scanner Pengembalian')

@section('content')
<style>
    /* 1. ANIMATIONS & THEME VARIABLES */
    :root {
        --primary-blue: #0f172a;
        --secondary-blue: #1e40af;
        --accent-red: #dc2626;
        --soft-red: #fef2f2;
    }

    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse-red {
        0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    /* 2. HEADER & NAVIGATION */
    .nav-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        animation: slideInUp 0.5s ease;
    }

    .btn-back-circle {
        width: 45px;
        height: 45px;
        background: white;
        color: var(--primary-blue);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .btn-back-circle:hover {
        background: var(--accent-red);
        color: white;
        transform: translateX(-5px);
        border-color: var(--accent-red);
    }

    /* 3. MAIN SCANNER CARD - THEME CONSISTENCY */
    .scanner-wrapper {
        max-width: 900px;
        margin: 0 auto;
        animation: slideInUp 0.7s ease;
    }

    .main-scan-card {
        background: white;
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .main-scan-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 8px;
        background: linear-gradient(90deg, var(--secondary-blue), var(--accent-red));
    }

    /* 4. STEP INDICATOR */
    .scan-steps {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 40px;
    }

    .step-item {
        background: #f8fafc;
        padding: 25px;
        border-radius: 24px;
        border: 2px solid transparent;
        transition: all 0.4s ease;
        text-align: center;
    }

    .step-item.active {
        background: #fff;
        border-color: var(--accent-red);
        animation: pulse-red 2s infinite;
    }

    .step-number {
        width: 38px;
        height: 38px;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-weight: 800;
        font-size: 15px;
    }

    .active .step-number {
        background: var(--secondary-blue);
        color: white;
    }

    .active h6 { color: var(--secondary-blue); }

    /* 5. INPUT REFINEMENT */
    .input-wrapper {
        position: relative;
        margin-bottom: 15px;
    }

    .input-wrapper i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-blue);
        font-size: 18px;
    }

    .scan-input-field {
        width: 100%;
        padding: 16px 16px 16px 55px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: var(--primary-blue);
    }

    .scan-input-field:focus {
        background: white;
        border-color: var(--secondary-blue);
        outline: none;
        box-shadow: 0 10px 20px rgba(30, 64, 175, 0.1);
    }

    .data-preview {
        margin-top: 10px;
        min-height: 25px;
    }

    .data-preview span {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        background: #f1f5f9;
        padding: 6px 14px;
        border-radius: 10px;
        display: inline-block;
    }

    .data-preview strong { color: var(--accent-red); }

    /* 6. SUBMIT BUTTON - BLUE GRADIENT */
    .btn-proses-final {
        width: 100%;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        color: white;
        border: none;
        padding: 20px;
        border-radius: 18px;
        font-weight: 800;
        font-size: 16px;
        margin-top: 30px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        letter-spacing: 1px;
    }

    .btn-proses-final:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.25);
        background: #000;
    }

    .text-info-scan {
        color: var(--accent-red);
        font-weight: 700;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- NAVIGATION HEADER --}}
    <div class="nav-header">
        <a href="{{ route('admin.riwayat.pengembalian.pengembalian') }}" class="btn-back-circle shadow-sm">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0" style="color: var(--primary-blue);">Scanner Pengembalian</h4>
            <p class="text-muted small mb-0">Proses pengembalian buku melalui pemindaian barcode</p>
        </div>
    </div>

    <div class="scanner-wrapper">
        <div class="main-scan-card">
            
            <div class="scan-steps">
                {{-- STEP 1: ANGGOTA --}}
                <div class="step-item active" id="stepAnggota">
                    <div class="step-number">1</div>
                    <h6 class="fw-bold mb-3">Identitas Anggota</h6>
                    <div class="input-wrapper">
                        <i class="bi bi-person-badge-fill"></i>
                        <input type="text" id="npm" class="scan-input-field" 
                               placeholder="Tap kartu / input NPM..." onkeydown="handleEnter('npm', event)" autofocus>
                    </div>
                    <div class="data-preview" id="previewAnggota">
                        <span>Standby...</span>
                    </div>
                </div>

                {{-- STEP 2: BUKU --}}
                <div class="step-item" id="stepBuku">
                    <div class="step-number">2</div>
                    <h6 class="fw-bold mb-3">Barcode Buku</h6>
                    <div class="input-wrapper">
                        <i class="bi bi-upc-scan"></i>
                        <input type="text" id="nomorBuku" class="scan-input-field" 
                               placeholder="Scan barcode buku..." onkeydown="handleEnter('buku', event)">
                    </div>
                    <div class="data-preview" id="previewBuku">
                        <span>Standby...</span>
                    </div>
                </div>
            </div>

            <button class="btn-proses-final" onclick="prosesPengembalian()">
                <i class="bi bi-arrow-counterclockwise fs-5"></i>
                SIMPAN PENGEMBALIAN
            </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Identik dengan logika peminjaman untuk konsistensi UX
function handleEnter(type, event){
    if(event.key === "Enter"){
        event.preventDefault();
        const value = event.target.value.trim();
        if(value){
            updateInfo(type, value);
            if(type === 'npm') {
                document.getElementById('nomorBuku').focus();
                document.getElementById('stepAnggota').classList.remove('active');
                document.getElementById('stepBuku').classList.add('active');
            }
        }
    }
}

function updateInfo(type, value){
    const preview = document.getElementById(type === "npm" ? "previewAnggota" : "previewBuku");
    preview.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';
    
    const url = type === "npm" 
        ? `/admin/riwayat/peminjaman/get-user/${value}` 
        : `/admin/riwayat/peminjaman/get-book/${value}`;

    fetch(url)
        .then(res=>res.json())
        .then(data=>{
            if(type === "npm"){
                preview.innerHTML = `<span>User: <strong>${data.nama || 'Tidak Ditemukan'}</strong></span>`;
            } else {
                preview.innerHTML = `<span>Judul: <strong>${data.judul || '-'}</strong></span>`;
            }
        })
        .catch(() => {
            preview.innerHTML = '<span class="text-danger">Gagal memuat data</span>';
        });
}

function prosesPengembalian() {
    const npm = document.getElementById('npm').value.trim();
    const nomorBuku = document.getElementById('nomorBuku').value.trim();

    if (!npm || !nomorBuku) {
        Swal.fire({ 
            icon: 'warning', 
            title: 'Data Belum Lengkap', 
            text: 'Pastikan Anggota dan Buku sudah di-scan!', 
            confirmButtonColor: '#1e40af' 
        });
        return;
    }

    fetch(`/admin/riwayat/peminjaman/get-user/${npm}`)
    .then(res => res.json())
    .then(data => {
        const peminjamTipe = data.peminjam_tipe || 'umum';
        if (peminjamTipe === 'guru') {
            Swal.fire({
                title: 'Input Jumlah',
                text: 'Berapa buku yang dikembalikan?',
                input: 'number',
                inputValue: 1,
                inputAttributes: { min: 1, step: 1 },
                showCancelButton: true,
                confirmButtonColor: '#1e40af',
                confirmButtonText: 'Proses'
            }).then((result) => {
                if (result.isConfirmed) submitPengembalianAdmin(npm, nomorBuku, result.value);
            });
        } else {
            submitPengembalianAdmin(npm, nomorBuku, 1);
        }
    });
}

function submitPengembalianAdmin(npm, nomorBuku, jumlah) {
    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch("{{ route('admin.riwayat.pengembalian.proses') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        body: JSON.stringify({ npm: npm, nomor_buku: nomorBuku, jumlah: jumlah })
    })
    .then(res => res.json())
    .then(data => {
        const isSuccess = data.message.toLowerCase().includes('berhasil');
        Swal.fire({
            icon: isSuccess ? 'success' : 'error',
            title: isSuccess ? 'Berhasil!' : 'Gagal!',
            text: data.message,
            confirmButtonColor: isSuccess ? '#1e40af' : '#dc2626'
        }).then(() => {
            if(isSuccess) location.reload();
        });
    });
}
</script>
@endsection