@extends('admin.layout')

@section('page-title', 'Scanner Peminjaman')

@section('content')
<style>
    /* 1. ANIMATIONS */
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse-orange {
        0% { box-shadow: 0 0 0 0 rgba(247, 147, 30, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(247, 147, 30, 0); }
        100% { box-shadow: 0 0 0 0 rgba(247, 147, 30, 0); }
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
        color: #1e293b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }

    .btn-back-circle:hover {
        background: var(--accent);
        color: white;
        transform: translateX(-5px);
    }

    /* 3. MAIN SCANNER CARD */
    .scanner-wrapper {
        max-width: 900px;
        margin: 0 auto;
        animation: slideInUp 0.7s ease;
    }

    .main-scan-card {
        background: white;
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        position: relative;
        overflow: hidden;
    }

    .main-scan-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 8px;
        background: linear-gradient(90deg, #f7931e, #ffb347);
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
        border-radius: 20px;
        border: 2px solid transparent;
        transition: all 0.4s ease;
        text-align: center;
    }

    .step-item.active {
        background: #fffefb;
        border-color: #f7931e;
        animation: pulse-orange 2s infinite;
    }

    .step-number {
        width: 35px;
        height: 35px;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-weight: 700;
        font-size: 14px;
    }

    .active .step-number {
        background: #f7931e;
        color: white;
    }

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
        color: #94a3b8;
        font-size: 18px;
    }

    .scan-input-field {
        width: 100%;
        padding: 16px 16px 16px 55px;
        background: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 15px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .scan-input-field:focus {
        background: white;
        border-color: #f7931e;
        outline: none;
        box-shadow: 0 10px 20px rgba(247, 147, 30, 0.1);
    }

    .data-preview {
        margin-top: 10px;
        min-height: 25px;
    }

    .data-preview span {
        font-size: 13px;
        color: #64748b;
        background: #f8fafc;
        padding: 5px 12px;
        border-radius: 8px;
        display: inline-block;
    }

    .data-preview strong { color: #f7931e; }

    /* 6. SUBMIT BUTTON */
    .btn-proses-final {
        width: 100%;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        border: none;
        padding: 18px;
        border-radius: 15px;
        font-weight: 700;
        font-size: 16px;
        margin-top: 30px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .btn-proses-final:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        background: #000;
    }
</style>

<div class="container-fluid py-4">
    
    {{-- NAVIGATION HEADER --}}
    <div class="nav-header">
        <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="btn-back-circle shadow-sm">
            <i class="bi bi-arrow-left fs-5"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Sistem Scanner</h4>
            <p class="text-muted small mb-0">Lakukan pemindaian kartu anggota dan barcode buku</p>
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
                        <i class="bi bi-person-badge"></i>
                        <input type="text" id="barcodeAnggota" class="scan-input-field" 
                               placeholder="Scan Kartu Anggota..." onkeydown="handleEnter('anggota', event)" autofocus>
                    </div>
                    <div class="data-preview" id="previewAnggota">
                        <span>Menunggu input...</span>
                    </div>
                </div>

                {{-- STEP 2: BUKU --}}
                <div class="step-item" id="stepBuku">
                    <div class="step-number">2</div>
                    <h6 class="fw-bold mb-3">Barcode Buku</h6>
                    <div class="input-wrapper">
                        <i class="bi bi-book"></i>
                        <input type="text" id="barcodeBuku" class="scan-input-field" 
                               placeholder="Scan Kode Buku..." onkeydown="handleEnter('buku', event)">
                    </div>
                    <div class="data-preview" id="previewBuku">
                        <span>Menunggu input...</span>
                    </div>
                </div>
            </div>

            <button class="btn-proses-final" onclick="prosesPeminjaman()">
                <i class="bi bi-check2-circle fs-4"></i>
                SIMPAN PEMINJAMAN
            </button>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i> 
                    Kursor otomatis fokus pada kolom input untuk mempercepat scan.
                </small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Logic UX: Berpindah step secara otomatis
function handleEnter(type, event){
    if(event.key === "Enter"){
        event.preventDefault();
        const value = event.target.value.trim();
        if(value){
            updateInfo(type, value);
            if(type === 'anggota') {
                document.getElementById('barcodeBuku').focus();
                document.getElementById('stepAnggota').classList.remove('active');
                document.getElementById('stepBuku').classList.add('active');
            }
        }
    }
}

function updateInfo(type, value){
    if(type==="anggota"){
        const preview = document.getElementById("previewAnggota");
        preview.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';
        
        fetch(`/admin/riwayat/peminjaman/get-user/${value}`)
            .then(res=>res.json())
            .then(data=>{
                preview.innerHTML = `<span>Anggota: <strong>${data.nama ?? 'Tidak Ditemukan'}</strong></span>`;
            })
            .catch(() => {
                preview.innerHTML = '<span class="text-danger">Gagal memuat data</span>';
            });
    }
    if(type==="buku"){
        const preview = document.getElementById("previewBuku");
        preview.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';

        fetch(`/admin/riwayat/peminjaman/get-book/${value}`)
            .then(res=>res.json())
            .then(data=>{
                preview.innerHTML = `<span>Judul: <strong>${data.judul ?? '-'}</strong> | Stok: ${data.jumlah ?? 0}</span>`;
            })
            .catch(() => {
                preview.innerHTML = '<span class="text-danger">Gagal memuat data</span>';
            });
    }
}

function prosesPeminjaman(){
    const npm = document.getElementById("barcodeAnggota").value.trim();
    const nomorBuku = document.getElementById("barcodeBuku").value.trim();

    if(!npm || !nomorBuku){ 
        Swal.fire({
            icon:'warning', 
            title:'Data Belum Lengkap', 
            text:'Pastikan Anggota dan Buku sudah di-scan!',
            confirmButtonColor: '#0f172a'
        }); 
        return; 
    }

    // Ambil data tipe user untuk limitasi
    fetch(`/admin/riwayat/peminjaman/get-user/${npm}`)
    .then(res => res.json())
    .then(data => {
        const peminjamTipe = data.peminjam_tipe || 'umum';
        if (peminjamTipe === 'guru') {
            Swal.fire({
                title: 'Jumlah Buku',
                text: 'Guru dapat meminjam lebih dari 1 buku:',
                input: 'number',
                inputAttributes: { min: 1, step: 1 },
                inputValue: 1,
                showCancelButton: true,
                confirmButtonText: 'Proses',
                confirmButtonColor: '#f7931e'
            }).then((result) => {
                if (result.isConfirmed) submitPeminjamanAdmin(npm, nomorBuku, result.value);
            });
        } else {
            submitPeminjamanAdmin(npm, nomorBuku, 1);
        }
    });
}

function submitPeminjamanAdmin(npm, nomorBuku, jumlah){
    Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    fetch("{{ route('admin.riwayat.peminjaman.proses') }}", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: JSON.stringify({npm: npm, nomor_buku: nomorBuku, jumlah: jumlah})
    })
    .then(res => res.json())
    .then(data => {
        Swal.fire({
            icon: data.message.toLowerCase().includes('berhasil') ? 'success' : 'error',
            title: data.message.toLowerCase().includes('berhasil') ? 'Berhasil!' : 'Gagal!',
            text: data.message,
            confirmButtonColor: '#f7931e'
        }).then(() => {
            if(data.message.toLowerCase().includes('berhasil')){
                location.reload();
            }
        });
    });
}
</script>
@endsection