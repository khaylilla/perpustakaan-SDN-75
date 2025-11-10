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
      <h5>Scan QR/NPM Anggota</h5>
      <button class="toggle-btn" onclick="toggleScan('anggota', event)">Gunakan Kamera</button>
      <div id="inputAnggota" class="scan-input">
        <input type="file" accept="image/*" onchange="handleFile('anggota', event)">
        <input type="text" id="barcodeAnggota" placeholder="Atau input manual NPM...">
      </div>
      <div class="info-scan" id="infoAnggota">Nama: <span id="namaAnggota">-</span></div>
      <div class="video-wrapper">
        <video id="videoAnggota" autoplay playsinline hidden></video>
        <div class="scanner-frame" id="frameAnggota" hidden>
          <div class="laser-line"></div>
        </div>
      </div>
      <canvas id="canvasAnggota" hidden></canvas>
    </div>

    {{-- Scan Nomor Buku --}}
    <div class="scan-box" id="scanBukuBox">
      <h5>Scan Barcode Nomor Buku</h5>
      <button class="toggle-btn" onclick="toggleScan('buku', event)">Gunakan Kamera</button>
      <div id="inputBuku" class="scan-input">
        <input type="file" accept="image/*" onchange="handleFile('buku', event)">
        <input type="text" id="barcodeBuku" placeholder="Atau input manual nomor buku...">
      </div>
      <div class="info-scan" id="infoBuku">Judul Buku: <span id="judulBuku">-</span> | Stok: <span id="stokBuku">-</span></div>
      <div class="video-wrapper">
        <video id="videoBuku" autoplay playsinline hidden></video>
        <div class="scanner-frame" id="frameBuku" hidden>
          <div class="laser-line"></div>
        </div>
      </div>
      <canvas id="canvasBuku" hidden></canvas>
    </div>

    <div class="submit-btn">
      <button onclick="prosesPeminjaman()">Simpan & Proses Peminjaman</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentStream = {};
let scanInterval = {};

function capitalize(str){ return str.charAt(0).toUpperCase() + str.slice(1); }

async function toggleScan(type,event){
  const video=document.getElementById(`video${capitalize(type)}`);
  const frame=document.getElementById(`frame${capitalize(type)}`);
  const input=document.getElementById(`input${capitalize(type)}`);
  const button=event.target;

  if(!video.hidden){ stopCamera(type); video.hidden=true; frame.hidden=true; input.hidden=false; button.textContent="Gunakan Kamera"; return; }

  try{
    const stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:"environment"}});
    video.srcObject=stream; currentStream[type]=stream;
    video.hidden=false; frame.hidden=false; input.hidden=true; button.textContent="Batalkan Kamera";
    startScanning(type);
  }catch(err){ alert("Kamera tidak dapat diakses. Pastikan izin kamera diaktifkan."); }
}

function startScanning(type){
  const video=document.getElementById(`video${capitalize(type)}`);
  const canvas=document.getElementById(`canvas${capitalize(type)}`);
  const ctx=canvas.getContext("2d");

  scanInterval[type]=setInterval(()=>{
    if(video.readyState===video.HAVE_ENOUGH_DATA){
      canvas.height=video.videoHeight; canvas.width=video.videoWidth;
      ctx.drawImage(video,0,0,canvas.width,canvas.height);
      const imageData=ctx.getImageData(0,0,canvas.width,canvas.height);
      const code=jsQR(imageData.data,imageData.width,imageData.height);
      if(code){
        document.getElementById(`barcode${capitalize(type)}`).value=code.data;
        stopCamera(type);
        document.querySelector(`#scan${capitalize(type)}Box .toggle-btn`).textContent="Gunakan Kamera";
        video.hidden=true; document.getElementById(`frame${capitalize(type)}`).hidden=true; document.getElementById(`input${capitalize(type)}`).hidden=false;
        updateInfo(type, code.data);
      }
    }
  },300);
}

function stopCamera(type){
  if(currentStream[type]){ currentStream[type].getTracks().forEach(track=>track.stop()); delete currentStream[type]; }
  if(scanInterval[type]){ clearInterval(scanInterval[type]); delete scanInterval[type]; }
}

function handleFile(type,event){
  const file=event.target.files[0]; if(!file)return;
  const reader=new FileReader();
  reader.onload=function(e){
    const img=new Image();
    img.onload=function(){
      const canvas=document.createElement("canvas");
      const ctx=canvas.getContext("2d");
      canvas.width=img.width; canvas.height=img.height;
      ctx.drawImage(img,0,0);
      const imageData=ctx.getImageData(0,0,img.width,img.height);
      const code=jsQR(imageData.data,imageData.width,imageData.height);
      if(code){ 
        document.getElementById(`barcode${capitalize(type)}`).value=code.data; 
        updateInfo(type, code.data); 
      } else alert("Tidak ditemukan barcode pada gambar ini.");
    };
    img.src=e.target.result;
  };
  reader.readAsDataURL(file);
}

function updateInfo(type, value){
  if(type==="anggota"){
    fetch(`/admin/riwayat/peminjaman/get-user/${value}`)
      .then(res=>res.json())
      .then(data=>{
        document.getElementById("namaAnggota").textContent = data.nama ?? "Tidak ditemukan";
      });
  }
  if(type==="buku"){
    fetch(`/admin/riwayat/peminjaman/get-book/${value}`)
      .then(res=>res.json())
      .then(data=>{
        document.getElementById("judulBuku").textContent = data.judul ?? "-";
        document.getElementById("stokBuku").textContent = data.jumlah ?? "-";
      });
  }
}

function prosesPeminjaman(){
  const npm = document.getElementById("barcodeAnggota").value.trim();
  const nomorBuku = document.getElementById("barcodeBuku").value.trim();

  if(!npm || !nomorBuku){ 
    Swal.fire({
      icon: 'warning',
      title: 'Oops!',
      text: 'Pastikan kedua barcode telah di-scan!'
    });
    return; 
  }

  fetch("{{ route('admin.riwayat.peminjaman.proses') }}",{
    method:"POST",
    headers:{
      "Content-Type":"application/json",
      "X-CSRF-TOKEN":"{{ csrf_token() }}"
    },
    body:JSON.stringify({npm:npm, nomor_buku:nomorBuku})
  })
  .then(res => res.json())
  .then(data => {
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: data.message ?? "Data peminjaman berhasil disimpan.",
      confirmButtonColor: '#f7931e'
    }).then(() => {
      // setelah klik OK, redirect ke halaman peminjaman
      window.location.href = "{{ route('admin.riwayat.peminjaman.peminjaman') }}";
    });

    // reset field input
    document.getElementById("barcodeAnggota").value = "";
    document.getElementById("barcodeBuku").value = "";
    document.getElementById("namaAnggota").textContent = "-";
    document.getElementById("judulBuku").textContent = "-";
    document.getElementById("stokBuku").textContent = "-";
  })
  .catch(err => {
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
