@extends('admin.layout')

@section('page-title', 'Presensi Digital')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
  body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
  .main-wrapper { max-width: 900px; margin: 0 auto; padding: 30px 20px; }

  /* Warna Utama: Biru (#0056b3), Merah (#dc3545), Putih (#ffffff) */

  /* Header & Navigation */
  .header-box { display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; }
  .btn-back-home { 
    display: flex; align-items: center; gap: 10px; padding: 10px 20px; 
    background: #fff; border-radius: 12px; border: 2px solid #0056b3;
    color: #0056b3; text-decoration: none; font-weight: 600; font-size: 14px;
    transition: 0.3s;
  }
  .btn-back-home:hover { background: #0056b3; color: #fff; transform: translateX(-5px); }

  /* Main Container */
  .glass-card { 
    background: #ffffff; 
    border-radius: 24px; padding: 40px; 
    box-shadow: 0 15px 35px rgba(0, 86, 179, 0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
  }
  
  /* Akses Garis Merah di atas kartu */
  .glass-card::before {
    content: ""; position: absolute; top: 0; left: 0; right: 0; height: 6px;
    background: linear-gradient(90deg, #0056b3 0%, #dc3545 100%);
  }
  
  /* Tabs */
  .nav-tabs-custom { 
    display: flex; background: #f1f5f9; padding: 6px; border-radius: 16px; 
    margin-bottom: 35px; border: 1px solid #e2e8f0;
  }
  .tab-item { 
    flex: 1; text-align: center; padding: 12px; border: none; background: none; 
    border-radius: 12px; font-size: 14px; font-weight: 700; color: #64748b; 
    cursor: pointer; transition: 0.3s; 
  }
  .tab-item.active { background: #0056b3; color: #ffffff; box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3); }

  /* Input Modern */
  .input-group-modern { position: relative; margin-bottom: 25px; }
  .input-group-modern i { 
    position: absolute; left: 20px; top: 50%; transform: translateY(-50%); 
    font-size: 20px; color: #0056b3; z-index: 10;
  }
  .form-control { 
    width: 100%; padding: 18px 20px 18px 55px; border-radius: 16px; 
    border: 2px solid #e2e8f0; background: #fff; outline: none; 
    font-size: 16px; transition: 0.3s;
  }
  .form-control:focus { 
    border-color: #0056b3; 
    box-shadow: 0 0 0 4px rgba(0, 86, 179, 0.1); 
  }
  .pulse-border:focus { animation: pulse-blue 2s infinite; }

  @keyframes pulse-blue {
    0% { box-shadow: 0 0 0 0 rgba(0, 86, 179, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(0, 86, 179, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 86, 179, 0); }
  }

  /* Result Card */
  .result-card { 
    background: #fff; border-radius: 20px; padding: 25px; margin-top: 25px; 
    border: 1px solid #e2e8f0; border-left: 6px solid #dc3545; /* Aksen Merah */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
  }
  .info-tag { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #dc3545; margin-bottom: 5px; display: block; }
  .info-name { font-size: 24px; font-weight: 800; color: #0056b3; margin: 0; }
  .badge-info { background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #0056b3; border: 1px solid #e2e8f0; }

  /* Buttons */
  .btn-confirm { 
    width: 100%; background: #0056b3; 
    color: white; border: none; padding: 18px; border-radius: 16px; 
    font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; 
    margin-top: 15px; box-shadow: 0 8px 15px rgba(0, 86, 179, 0.2);
  }
  .btn-confirm:hover { background: #004494; transform: translateY(-2px); box-shadow: 0 12px 20px rgba(0, 86, 179, 0.3); }
  
  .btn-secondary-custom {
    background: #ffffff; color: #dc3545; border: 2px solid #dc3545;
  }
  .btn-secondary-custom:hover { background: #dc3545; color: #fff; }

  /* Upload Zone */
  .upload-zone { 
    border: 2px dashed #0056b3; border-radius: 20px; padding: 40px; 
    text-align: center; cursor: pointer; background: #f0f7ff; transition: 0.3s;
  }
  .upload-zone:hover { border-color: #dc3545; background: #fff5f5; }
  .upload-zone i { font-size: 50px; color: #0056b3; margin-bottom: 15px; display: block; }
  
  /* Progress */
  .progress-bar-fill { background: linear-gradient(90deg, #0056b3, #dc3545); height: 100%; transition: 0.3s; }

  /* Status Bar */
  .status-bar { display: flex; gap: 10px; background: #fff; padding: 8px 15px; border-radius: 20px; border: 1px solid #e2e8f0; }
  .status-indicator { width: 10px; height: 10px; border-radius: 50%; background: #10b981; align-self: center; box-shadow: 0 0 8px #10b981; }
  .status-text { font-size: 12px; font-weight: 700; color: #1e293b; }
</style>

<div class="main-wrapper">
  <div class="header-box">
    <a href="{{ route('admin.dataabsen') }}" class="btn-back-home">
      <i class="bi bi-arrow-left"></i> Kembali 
    </a>
    <div class="status-bar">
      <div class="status-indicator"></div>
      <span class="status-text">SISTEM AKTIF</span>
    </div>
  </div>

  <div class="glass-card">
    <div class="nav-tabs-custom">
      <button onclick="switchTab('tab1')" class="tab-item active" id="btn-tab1">📱 SCAN BARCODE</button>
      <button onclick="switchTab('tab2')" class="tab-item" id="btn-tab2">📝 MANUAL</button>
      <button onclick="switchTab('tab3')" class="tab-item" id="btn-tab3">📤 IMPORT</button>
    </div>

    <div id="tab1" class="tab-content">
      <div class="input-group-modern">
        <i class="bi bi-qr-code-scan"></i>
        <input type="text" id="barcodeAnggota" class="form-control pulse-border" placeholder="Arahkan scanner ke barcode..." autofocus>
      </div>

      <div id="scanResult" class="result-card">
        <span class="info-tag">Hasil Pemindaian</span>
        <h2 class="info-name" id="namaAnggota">-</h2>
        <div class="info-sub" style="display: flex; gap: 10px; margin-top: 15px;">
          <span class="badge-info"><i class="bi bi-person-badge"></i> <span id="identifierType">-</span></span>
          <span class="badge-info"><i class="bi bi-key"></i> <span id="identifierValue">-</span></span>
        </div>
      </div>
      
      <button class="btn-confirm" onclick="simpanAbsen()">KONFIRMASI KEHADIRAN</button>
    </div>

    <div id="tab2" class="tab-content" style="display:none;">
      <div class="input-group-modern">
        <i class="bi bi-search"></i>
        <input type="text" id="manualIdentifier" class="form-control" placeholder="Masukkan NISN / NIP / Email...">
      </div>
      
      <div id="manualResult" class="result-card" style="border-left-color: #0056b3;">
        <span class="info-tag">Informasi Anggota</span>
        <h2 class="info-name" id="namaManual">-</h2>
        <div class="info-sub" style="display: flex; gap: 10px; margin-top: 15px;">
          <span class="badge-info" id="identifierTypeManual">-</span>
          <span class="badge-info" id="identifierValueManual">-</span>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <button class="btn-confirm btn-secondary-custom" onclick="cariDataManual()">CARI DATA</button>
        <button class="btn-confirm" onclick="simpanAbsenManual()">SIMPAN ABSEN</button>
      </div>
    </div>

    <div id="tab3" class="tab-content" style="display:none;">
      <div class="upload-zone" onclick="document.getElementById('mediaUpload').click()">
        <i class="bi bi-cloud-arrow-up"></i>
        <p id="uploadText" style="font-weight: 700; color: #1e293b;">Klik atau seret file ke sini</p>
        <span style="font-size: 13px; color: #64748b;">(Excel, CSV, atau Foto Barcode)</span>
        <input type="file" id="mediaUpload" hidden accept=".csv, .xlsx, .xls, image/*">
        <img id="previewImage" style="max-width: 150px; margin-top: 15px; border-radius: 12px; display: none; border: 3px solid #0056b3;">
      </div>

      <div id="fileDetail" class="result-card" style="display:none;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div>
            <span class="info-tag">File Terpilih</span>
            <p id="fileName" style="font-weight: 700; margin:0; color: #0056b3;">-</p>
          </div>
          <span id="fileType" class="badge-info">-</span>
        </div>
      </div>

      <div id="barcodeDetected" class="result-card" style="display:none; background: #fdf2f2; border: 1px solid #fecaca; border-left-color: #dc3545;">
        <span class="info-tag">Barcode Terdeteksi</span>
        <p id="detectedCode" style="font-size: 20px; font-weight: 800; color: #dc3545; margin: 0;">-</p>
      </div>

      <div id="importProgress" class="progress-container" style="display:none; margin-top: 20px;">
        <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
          <span style="font-size: 13px; font-weight: 700; color: #0056b3;">Memproses Data...</span>
          <span style="font-size: 13px; font-weight: 700;"><span id="currProgress">0</span>/<span id="totalProgress">0</span></span>
        </div>
        <div style="background: #e2e8f0; height: 12px; border-radius: 10px; overflow: hidden;">
            <div id="progressBar" class="progress-bar-fill" style="width: 0%"></div>
        </div>
      </div>

      <button id="processBtn" class="btn-confirm" onclick="handleMediaProcess()">PROSES & IMPORT DATA</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.10.2/dist/quagga.js"></script>

<script>
  let selectedFile = null;

  window.addEventListener('load', function() {
    document.getElementById('barcodeAnggota').focus();
  });

  function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-item').forEach(el => el.classList.remove('active'));
    document.getElementById(tabName).style.display = 'block';
    document.getElementById('btn-' + tabName).classList.add('active');
    
    if(tabName === 'tab1') document.getElementById('barcodeAnggota').focus();
    if(tabName === 'tab2') document.getElementById('manualIdentifier').focus();
  }

  document.getElementById('barcodeAnggota').addEventListener('keydown', function(event) {
    if(event.key === 'Enter'){
      event.preventDefault();
      const identifier = this.value.trim();
      if(identifier){
        setTimeout(() => {
          updateInfo(identifier);
          simpanAbsen();
        }, 50);
      }
    }
  });

  document.getElementById('manualIdentifier').addEventListener('keydown', function(event) {
    if(event.key === 'Enter'){
      event.preventDefault();
      cariDataManual();
    }
  });

  function updateInfo(identifier){
    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(data.nama){
          let typeLabel = '';
          if(data.type === 'users'){ typeLabel = 'NISN'; }
          else if(data.type === 'guru'){ typeLabel = 'NIP'; }
          else { typeLabel = 'Email'; }
          
          document.getElementById("namaAnggota").textContent = data.nama;
          document.getElementById("identifierType").textContent = typeLabel;
          document.getElementById("identifierValue").textContent = identifier;
        } else {
          document.getElementById("namaAnggota").textContent = "Tidak ditemukan";
        }
      });
  }

  function updateInfoManual(identifier){
    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(data.nama){
          let typeLabel = '';
          if(data.type === 'users'){ typeLabel = 'NISN'; }
          else if(data.type === 'guru'){ typeLabel = 'NIP'; }
          else { typeLabel = 'Email'; }
          
          document.getElementById("namaManual").textContent = data.nama;
          document.getElementById("identifierTypeManual").textContent = typeLabel;
          document.getElementById("identifierValueManual").textContent = identifier;
        } else {
          document.getElementById("namaManual").textContent = "Tidak ditemukan";
        }
      });
  }

  function cariDataManual(){
    const identifier = document.getElementById('manualIdentifier').value.trim();
    if(!identifier){
      Swal.fire({icon:'warning', title:'Oops!', text:'Masukkan NISN/NIP/Email!'});
      return;
    }
    updateInfoManual(identifier);
  }

  function simpanAbsen(){
    const identifier = document.getElementById("barcodeAnggota").value.trim();
    if(!identifier){ 
      Swal.fire({icon:'warning', title:'Oops!', text:'Scan barcode terlebih dahulu!'}); 
      return; 
    }

    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(!data.nama){
          Swal.fire({icon:'error', title:'Gagal!', text:'Anggota tidak ditemukan.'});
          document.getElementById('barcodeAnggota').value = '';
          return;
        }

        const tanggal = new Date().toISOString().split('T')[0];

        fetch("{{ route('admin.absen.scan.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ npm: identifier, nama: data.nama, tanggal })
        })
        .then(res => res.json())
        .then(body => {
          Swal.fire({icon:'success', title:'Berhasil!', text:body.message || 'Absen tersimpan.', timer: 2000, showConfirmButton: false})
            .then(() => {
              document.getElementById('barcodeAnggota').value = '';
              document.getElementById("namaAnggota").textContent = '-';
              document.getElementById("identifierType").textContent = '-';
              document.getElementById("identifierValue").textContent = '-';
              document.getElementById('barcodeAnggota').focus();
            });
        });
      });
  }

  function simpanAbsenManual(){
    const identifier = document.getElementById('manualIdentifier').value.trim();
    if(!identifier){ 
      Swal.fire({icon:'warning', title:'Oops!', text:'Masukkan NISN/NIP/Email!'}); 
      return; 
    }

    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(!data.nama){
          Swal.fire({icon:'error', title:'Gagal!', text:'Anggota tidak ditemukan.'});
          return;
        }

        const tanggal = new Date().toISOString().split('T')[0];

        fetch("{{ route('admin.absen.scan.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ npm: identifier, nama: data.nama, tanggal })
        })
        .then(res => res.json())
        .then(body => {
          Swal.fire({icon:'success', title:'Berhasil!', text:body.message || 'Absen tersimpan.'})
            .then(() => {
              document.getElementById('manualIdentifier').value = '';
              document.getElementById("namaManual").textContent = '-';
              document.getElementById("identifierTypeManual").textContent = '-';
              document.getElementById("identifierValueManual").textContent = '-';
            });
        });
      });
  }

  document.getElementById('mediaUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if(!file) return;
    
    selectedFile = file;
    const reader = new FileReader();
    
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('fileDetail').style.display = 'block';
    document.getElementById('barcodeDetected').style.display = 'none';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileType').textContent = file.type || 'File Data';

    if(file.type.startsWith('image/')) {
      reader.onload = (event) => {
        const img = document.getElementById('previewImage');
        img.src = event.target.result;
        img.style.display = 'block';
        document.getElementById('uploadText').style.display = 'none';
      };
      reader.readAsDataURL(file);
    } else {
      document.getElementById('uploadText').innerHTML = `File: <strong>${file.name}</strong>`;
    }
  });

  async function handleMediaProcess() {
    if(!selectedFile) {
      Swal.fire('Oops!', 'Pilih file atau gambar dulu.', 'warning');
      return;
    }

    if(selectedFile.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        Quagga.decodeSingle({
          src: e.target.result,
          decoder: { readers: ["code_128_reader", "ean_reader", "upc_reader"] }
        }, function(result) {
          if(result && result.codeResult) {
            const code = result.codeResult.code;
            document.getElementById('barcodeDetected').style.display = 'block';
            document.getElementById('detectedCode').textContent = code;
            
            Swal.fire({
              title: 'Barcode Ditemukan!',
              text: `Simpan absen untuk: ${code}?`,
              icon: 'info',
              showCancelButton: true,
              confirmButtonText: 'Ya, Simpan'
            }).then((res) => { 
              if(res.isConfirmed) finalSimpan(code); 
            });
          } else {
            Swal.fire('Gagal', 'Barcode tidak terbaca. Coba gambar lain.', 'error');
          }
        });
      };
      reader.readAsDataURL(selectedFile);
    } else {
      const reader = new FileReader();
      reader.onload = function(e) {
        let data = [];
        if(selectedFile.name.endsWith('.csv')){
          data = e.target.result.trim().split('\n').map(r => r.trim());
        } else {
          const wb = XLSX.read(e.target.result, {type: 'array'});
          const ws = wb.Sheets[wb.SheetNames[0]];
          data = XLSX.utils.sheet_to_json(ws, {header: 1})
            .map(r => (r[0] || '').toString().trim())
            .filter(r => r);
        }
        processBatchAbsen(data.filter(i => i));
      };
      if(selectedFile.name.endsWith('.csv')) reader.readAsText(selectedFile);
      else reader.readAsArrayBuffer(selectedFile);
    }
  }

  function processBatchAbsen(identifiers){
    let successCount = 0;
    let failCount = 0;
    let current = 0;
    const total = identifiers.length;
    const tanggal = new Date().toISOString().split('T')[0];

    document.getElementById('importProgress').style.display = 'block';
    document.getElementById('totalProgress').textContent = total;

    function processNext(){
      if(current >= total){
        document.getElementById('currProgress').textContent = total;
        document.getElementById('progressBar').style.width = '100%';
        
        Swal.fire({
          icon: 'info',
          title: 'Selesai!',
          html: `Berhasil: <strong>${successCount}</strong><br>Gagal: <strong>${failCount}</strong>`,
          confirmButtonColor: '#4a4ca4'
        });
        return;
      }

      const identifier = identifiers[current].trim();
      current++;
      
      document.getElementById('currProgress').textContent = current;
      document.getElementById('progressBar').style.width = (current / total * 100) + '%';

      if(!identifier){
        failCount++;
        processNext();
        return;
      }

      fetch(`/admin/absen/get-user/${identifier}`)
        .then(res => res.json())
        .then(data => {
          if(!data.nama){
            failCount++;
            processNext();
            return;
          }

          fetch("{{ route('admin.absen.scan.store') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ npm: identifier, nama: data.nama, tanggal })
          })
          .then(res => res.json())
          .then(body => {
            if(body.message && body.message.includes('berhasil')){
              successCount++;
            } else {
              failCount++;
            }
            processNext();
          })
          .catch(() => {
            failCount++;
            processNext();
          });
        })
        .catch(() => {
          failCount++;
          processNext();
        });
    }
    processNext();
  }

  function finalSimpan(identifier){
    const tanggal = new Date().toISOString().split('T')[0];
    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(!data.nama){
          Swal.fire({icon:'error', title:'Gagal!', text:'Anggota tidak ditemukan.'});
          return;
        }

        fetch("{{ route('admin.absen.scan.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ npm: identifier, nama: data.nama, tanggal })
        })
        .then(res => res.json())
        .then(body => {
          Swal.fire({icon:'success', title:'Berhasil!', text:body.message || 'Absen tersimpan.', timer: 2000, showConfirmButton: false})
            .then(() => {
              document.getElementById('mediaUpload').value = '';
              document.getElementById('previewImage').style.display = 'none';
              document.getElementById('barcodeDetected').style.display = 'none';
              document.getElementById('detectedCode').textContent = '-';
            });
        });
      });
  }
</script>

@endsection