@extends('admin.layout')

@section('page-title', 'Scan Peminjaman Buku')

@section('content')
<style>
  .info-boxes { display: flex; flex-wrap: wrap; gap: 25px; margin-bottom: 25px; }
  .info-box { background: linear-gradient(135deg, #f7931e, #ffb347); color: white; border-radius: 20px; width: 320px; padding: 25px 30px; box-shadow: 0 6px 18px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: space-between; text-decoration: none; transition: transform 0.25s ease, box-shadow 0.25s ease; }
  .info-box:hover { transform: translateY(-5px); box-shadow: 0 10px 22px rgba(0,0,0,0.2); }
  .info-box i { font-size: 42px; opacity: 0.85; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 18px; }
  .info-box-content p { font-size: 14px; margin-top: 5px; }

  .scan-container { display: flex; flex-direction: column; gap: 30px; background: #fff; padding: 50px; border-radius: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.12); max-width: 850px; margin: 0 auto; }

  /* ✨ Container scan tetap padding oranye seperti awal */
  .scan-box { border: 3px dashed #f7931e; border-radius: 16px; padding: 35px; text-align: center; transition: 0.3s; position: relative; }
  .scan-box:hover { background: #fff7ef; }
  .scan-box h5 { font-weight: 700; margin-bottom: 15px; }

  .scan-input { display: flex; justify-content: center; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 15px; }
  .toggle-btn { background: linear-gradient(135deg, #f7931e, #ffb347); border: none; color: white; padding: 10px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
  .toggle-btn:hover { transform: scale(1.05); }

  video { width: 100%; max-width: 600px; border-radius: 15px; border: 3px solid #f7931e; margin-top: 10px; position: relative; }
  .video-wrapper { position: relative; display: inline-block; }

  /* 🚀 Futuristic Orange Scanner Frame */
  .scanner-frame {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 220px;
    height: 220px;
    transform: translate(-50%, -50%);
    border-radius: 16px;
    pointer-events: none;
    overflow: hidden;
    border: 4px solid #f7931e; /* orange border */
    box-shadow: 0 0 20px rgba(247, 147, 30, 0.4), 0 0 30px rgba(247, 147, 30, 0.3);
    animation: pulse-frame 2.5s infinite ease-in-out;
  }

  @keyframes pulse-frame {
    0%,100% { box-shadow: 0 0 20px rgba(247,147,30,0.4), 0 0 30px rgba(247,147,30,0.3); }
    50% { box-shadow: 0 0 35px rgba(247,147,30,0.6), 0 0 45px rgba(247,147,30,0.5); }
  }

  /* 🚀 Futuristic Orange Laser Line */
  .laser-line {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: #f7931e; /* solid orange */
    box-shadow: 0 0 15px #f7931e, 0 0 25px rgba(247,147,30,0.5);
    animation: laser-move 2s linear infinite, laser-glow 1.5s ease-in-out infinite alternate;
  }

  @keyframes laser-move {
    0% { top: 0; }
    50% { top: calc(100% - 4px); }
    100% { top: 0; }
  }

  @keyframes laser-glow {
    0% { opacity: 0.7; }
    50% { opacity: 1; }
    100% { opacity: 0.7; }
  }

  .submit-btn { text-align: center; }
  .submit-btn button { background: linear-gradient(135deg, #f7931e, #ffb347); border: none; color: white; font-weight: 700; padding: 14px 40px; border-radius: 10px; font-size: 16px; box-shadow: 0 6px 14px rgba(0,0,0,0.12); transition: all 0.3s; }
  .submit-btn button:hover { transform: translateY(-3px); box-shadow: 0 10px 18px rgba(0,0,0,0.2); }

  .info-scan { margin-top: 12px; font-size: 15px; font-weight: 600; color: #333; }
  .info-scan span { font-weight: 700; color: #f7931e; }
</style>

<div class="container-fluid">
  <div class="info-boxes">
    <a href="{{ route('admin.riwayat.peminjaman.scan') }}" class="info-box">
      <div class="info-box-content">
        <h5>Scan Peminjaman Buku</h5>
        <p>Scan barcode anggota & buku</p>
      </div>
      <i class="bi bi-upc-scan"></i>
    </a>

    <a href="{{ route('admin.riwayat.peminjaman.peminjaman') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Peminjaman Buku</h5>
        <p>Daftar peminjaman yang sedang berlangsung</p>
      </div>
      <i class="bi bi-book-half"></i>
    </a>
  </div>

  <div class="scan-container">
    {{-- Scan Kartu Anggota --}}
    <div class="scan-box" id="scanAnggotaBox">
      <h5>Scan Kartu Anggota</h5>
      <div id="inputAnggota" class="scan-input" style="flex-direction: column; align-items: stretch;">
        <input type="text" id="barcodeAnggota" placeholder="Scan / input NIP / NPM..." onkeydown="handleEnter('anggota', event)" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;">
        <small style="color: #666; margin-top: 8px;">💡 Gunakan scanner barcode atau input manual, lalu tekan Enter</small>
      </div>
      <div class="info-scan" id="infoAnggota">Nama: <span id="namaAnggota">-</span></div>
    </div>

    {{-- Scan Nomor Buku --}}
    <div class="scan-box" id="scanBukuBox">
      <h5>Scan Nomor Buku</h5>
      <div id="inputBuku" class="scan-input" style="flex-direction: column; align-items: stretch;">
        <input type="text" id="barcodeBuku" placeholder="Scan / input nomor buku..." onkeydown="handleEnter('buku', event)" style="width: 100%; padding: 12px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;">
        <small style="color: #666; margin-top: 8px;">💡 Gunakan scanner barcode atau input manual, lalu tekan Enter</small>
      </div>
      <div class="info-scan" id="infoBuku">Judul Buku: <span id="judulBuku">-</span> | Stok: <span id="stokBuku">-</span></div>
    </div>

    <div class="submit-btn">
      <button onclick="prosesPeminjaman()">Simpan & Proses Peminjaman</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentStream = {};
let scanInterval = {};

function capitalize(str){ return str.charAt(0).toUpperCase() + str.slice(1); }

// ✨ Handle Enter key dari scanner atau input manual
function handleEnter(type, event){
  if(event.key === "Enter"){
    event.preventDefault();
    const value = event.target.value.trim();
    if(value){
      updateInfo(type, value);
    }
  }
}

function updateInfo(type, value){
  if(type==="anggota"){
    fetch(`/admin/riwayat/peminjaman/get-user/${value}`)
      .then(res=>res.json())
      .then(data=>{
        document.getElementById("namaAnggota").textContent = data.nama ?? "Tidak ditemukan";
      })
      .catch(err => {
        document.getElementById("namaAnggota").textContent = "Tidak ditemukan";
      });
  }
  if(type==="buku"){
    fetch(`/admin/riwayat/peminjaman/get-book/${value}`)
      .then(res=>res.json())
      .then(data=>{
        document.getElementById("judulBuku").textContent = data.judul ?? "-";
        document.getElementById("stokBuku").textContent = data.jumlah ?? "-";
      })
      .catch(err => {
        document.getElementById("judulBuku").textContent = "-";
        document.getElementById("stokBuku").textContent = "-";
      });
  }
}

function prosesPeminjaman(){
  const npm = document.getElementById("barcodeAnggota").value.trim();
  const nomorBuku = document.getElementById("barcodeBuku").value.trim();

  if(!npm || !nomorBuku){ 
    Swal.fire({icon:'warning', title:'Oops!', text:'Pastikan kedua barcode telah di-scan!'}); 
    return; 
  }

  // ✨ CEK TIPE USER untuk menentukan limit jumlah buku
  fetch(`/admin/riwayat/peminjaman/get-user/${npm}`)
    .then(res => res.json())
    .then(data => {
      const peminjamTipe = data.peminjam_tipe || 'umum';
      
      if (peminjamTipe === 'guru') {
        // GURU: Popup input jumlah (unlimited)
        Swal.fire({
          title: '📚 Jumlah Buku',
          text: 'Berapa buku yang ingin dipinjam? (Tanpa batas)',
          input: 'number',
          inputAttributes: {
            min: 1,
            step: 1
          },
          inputValue: 1,
          showCancelButton: true,
          confirmButtonText: 'Proses',
          confirmButtonColor: '#f7931e',
          cancelButtonColor: '#6c757d',
          preConfirm: (jumlah) => {
            if (!jumlah || jumlah < 1) {
              Swal.showValidationMessage('Minimal 1 buku');
              return false;
            }
            return jumlah;
          }
        }).then((result) => {
          if (result.isConfirmed) {
            submitPeminjamanAdmin(npm, nomorBuku, result.value);
          }
        });
      } else {
        // UMUM/SISWA: Auto 1 buku, langsung proses
        Swal.fire({
          title: '📚 Jumlah Buku',
          text: peminjamTipe.charAt(0).toUpperCase() + peminjamTipe.slice(1) + ' hanya boleh meminjam 1 buku. Lanjutkan?',
          icon: 'info',
          showCancelButton: true,
          confirmButtonText: 'Proses',
          confirmButtonColor: '#f7931e',
          cancelButtonColor: '#6c757d'
        }).then((result) => {
          if (result.isConfirmed) {
            submitPeminjamanAdmin(npm, nomorBuku, 1);
          }
        });
      }
    })
    .catch(err => {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Tidak dapat mengambil data user',
        confirmButtonColor: '#f7931e'
      });
    });
}

// ✨ SUBMIT PEMINJAMAN KE BACKEND
function submitPeminjamanAdmin(npm, nomorBuku, jumlah){
  fetch("{{ route('admin.riwayat.peminjaman.proses') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    body: JSON.stringify({npm: npm, nomor_buku: nomorBuku, jumlah: jumlah})
  })
  .then(res => res.json().then(data => ({ status: res.status, body: data })))
  .then(({status, body}) => {
    if (status === 200) {
      if (body.message.toLowerCase().includes('habis') || body.message.toLowerCase().includes('stok 0')) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: body.message,
          confirmButtonColor: '#f7931e'
        });
      } else {
        Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: body.message,
          confirmButtonColor: '#f7931e'
        }).then(() => {
          document.getElementById("barcodeAnggota").value="";
          document.getElementById("barcodeBuku").value="";
          document.getElementById("namaAnggota").textContent="-";
          document.getElementById("judulBuku").textContent="-";
          document.getElementById("stokBuku").textContent="-";
          document.getElementById("barcodeAnggota").focus();
        });
      }
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: body.message,
        confirmButtonColor: '#f7931e'
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: 'error', 
      title: 'Gagal', 
      text: 'Gagal mengirim data ke server.', 
      confirmButtonColor: '#f7931e'
    });
  });
}
</script>
@endsection
