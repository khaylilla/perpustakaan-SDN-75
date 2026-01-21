@extends('admin.layout')

@section('page-title', 'Presensi Digital')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
  body { font-family: 'Inter', sans-serif; background-color: #f4f7fa; }
  .main-wrapper { max-width: 800px; margin: 0 auto; padding: 20px; }

  /* Info Box */
  .stats-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 30px; }
  .stat-card { background: #ffffff; border-radius: 16px; padding: 18px; display: flex; align-items: center; gap: 12px; border: 1px solid rgba(0,0,0,0.05); text-decoration: none; color: inherit; transition: 0.3s; }
  .stat-card:hover { transform: translateY(-3px); border-color: #4a4ca4; }
  .stat-icon { width: 45px; height: 45px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; background: #f0f2ff; color: #4a4ca4; }
  .stat-info h6 { margin: 0; font-size: 13px; color: #6c757d; }
  .stat-info span { font-weight: 700; font-size: 15px; color: #2d3436; }

  /* Container */
  .glass-card { background: #ffffff; border-radius: 20px; padding: 35px; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border: 1px solid #eee; }
  
  /* Tabs */
  .nav-tabs-custom { display: flex; background: #f1f3f9; padding: 5px; border-radius: 12px; margin-bottom: 25px; }
  .tab-item { flex: 1; text-align: center; padding: 10px; border: none; background: none; border-radius: 8px; font-size: 14px; font-weight: 600; color: #6c757d; cursor: pointer; transition: 0.3s; }
  .tab-item.active { background: #ffffff; color: #4a4ca4; box-shadow: 0 4px 10px rgba(0,0,0,0.04); }

  /* Upload Zone */
  .upload-zone { border: 2px dashed #e2e8f0; border-radius: 16px; padding: 30px; text-align: center; transition: 0.3s; cursor: pointer; position: relative; }
  .upload-zone:hover { border-color: #4a4ca4; background: #f8faff; }
  .upload-zone i { font-size: 40px; color: #cbd5e1; margin-bottom: 10px; display: block; }
  .upload-zone p { font-size: 14px; color: #64748b; margin: 0; }
  #previewImage { max-width: 100%; max-height: 200px; border-radius: 12px; margin-top: 15px; display: none; margin-left: auto; margin-right: auto; border: 1px solid #ddd; }

  /* Result & Progress */
  .result-box { background: #f8fafc; border-radius: 12px; padding: 15px; margin-top: 20px; display: none; border: 1px solid #e2e8f0; }
  .progress-wrapper { margin-top: 20px; display: none; }
  .progress-bar-bg { background: #e2e8f0; height: 8px; border-radius: 10px; overflow: hidden; margin-top: 8px; }
  .progress-bar-fill { background: #4a4ca4; height: 100%; width: 0%; transition: width 0.3s; }

  /* Buttons */
  .btn-primary-modern { width: 100%; background: #4a4ca4; color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 15px; }
  .btn-primary-modern:hover { opacity: 0.9; transform: translateY(-1px); }
  
  /* Form Input */
  .form-control { width:100%; padding:14px; border-radius:10px; border:2px solid #eef0f7; outline:none; font-family: 'Inter', sans-serif; }
  .form-control:focus { border-color: #4a4ca4; box-shadow: 0 0 0 3px rgba(74, 76, 164, 0.1); }
</style>

<div class="main-wrapper">
  <div class="stats-container">
    <a href="{{ route('admin.absen.scan') }}" class="stat-card">
      <div class="stat-icon"><i class="bi bi-qr-code-scan"></i></div>
      <div class="stat-info"><h6>Mode</h6><span>Auto Scan</span></div>
    </a>
    <a href="{{ route('admin.dataabsen') }}" class="stat-card">
      <div class="stat-icon"><i class="bi bi-journal-text"></i></div>
      <div class="stat-info"><h6>Riwayat</h6><span>Data Absen</span></div>
    </a>
  </div>

  <div class="glass-card">
    <div class="nav-tabs-custom">
      <button onclick="switchTab('tab1')" class="tab-item active" id="btn-tab1">📱 Scanner</button>
      <button onclick="switchTab('tab2')" class="tab-item" id="btn-tab2">📝 Input Manual</button>
      <button onclick="switchTab('tab3')" class="tab-item" id="btn-tab3">📤 Import Media</button>
    </div>

    <!-- TAB 1: SCANNER -->
    <div id="tab1" class="tab-content">
      <div style="margin-bottom: 20px;">
        <input type="text" id="barcodeAnggota" class="form-control" placeholder="Arahkan scanner ke barcode..." autofocus>
      </div>
      <div id="scanResult" class="result-box" style="display:block; border-left: 4px solid #4a4ca4;">
        <div style="display:flex; justify-content:space-between; font-size:14px; margin-bottom:8px;">
          <span style="color:#64748b;">Nama Terdeteksi:</span>
          <span id="namaAnggota" style="font-weight:700; color:#2d3436;">-</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:12px; color:#6c757d;">
          <span id="identifierType">-</span>
          <span id="identifierValue" style="font-family:monospace;">-</span>
        </div>
      </div>
      <button class="btn-primary-modern" onclick="simpanAbsen()">Konfirmasi Absen</button>
    </div>

    <!-- TAB 2: MANUAL INPUT -->
    <div id="tab2" class="tab-content" style="display:none;">
      <div style="margin-bottom: 15px;">
        <input type="text" id="manualIdentifier" class="form-control" placeholder="Masukkan NISN / NIP / Email...">
      </div>
      <div id="manualResult" class="result-box" style="display:block; border-left: 4px solid #27ae60;">
        <div style="display:flex; justify-content:space-between; font-size:14px; margin-bottom:8px;">
          <span style="color:#64748b;">Nama Terdeteksi:</span>
          <span id="namaManual" style="font-weight:700; color:#2d3436;">-</span>
        </div>
        <div style="display:flex; justify-content:space-between; font-size:12px; color:#6c757d;">
          <span id="identifierTypeManual">-</span>
          <span id="identifierValueManual" style="font-family:monospace;">-</span>
        </div>
      </div>
      <button class="btn-primary-modern" onclick="cariDataManual()">Cari & Verifikasi</button>
      <button class="btn-primary-modern" style="background:#27ae60; margin-top:8px;" onclick="simpanAbsenManual()">Simpan Data</button>
    </div>

    <!-- TAB 3: IMPORT MEDIA -->
    <div id="tab3" class="tab-content" style="display:none;">
      <div class="upload-zone" onclick="document.getElementById('mediaUpload').click()" style="margin-bottom:20px;">
        <i class="bi bi-cloud-arrow-up"></i>
        <p id="uploadText">Klik untuk upload File (.xlsx/.csv) atau Gambar Barcode</p>
        <input type="file" id="mediaUpload" hidden accept=".csv, .xlsx, .xls, image/*">
        <img id="previewImage">
      </div>

      <div id="fileDetail" class="result-box">
        <div style="display:flex; justify-content:space-between; font-size:13px;">
          <span>Berkas:</span>
          <div>
            <span id="fileName" style="color:#4a4ca4; font-weight:600;">-</span>
            <span id="fileType" style="margin-left:10px; font-size:11px; padding:2px 6px; background:#ddd; border-radius:4px;">-</span>
          </div>
        </div>
      </div>

      <div id="barcodeDetected" class="result-box" style="background:#eef2ff; border: 1px solid #4a4ca4;">
        <p style="margin:0 0 8px 0; font-size:12px; color:#4a4ca4; font-weight:600;">Barcode Terdeteksi:</p>
        <span id="detectedCode" style="font-size:16px; font-weight:700; color:#2d3436;">-</span>
      </div>

      <div id="importProgress" class="progress-wrapper">
        <div style="display:flex; justify-content:space-between; font-size:12px; font-weight:600; margin-bottom:8px;">
          <span>Memproses Antrean</span>
          <span><span id="currProgress">0</span>/<span id="totalProgress">0</span></span>
        </div>
        <div class="progress-bar-bg"><div id="progressBar" class="progress-bar-fill"></div></div>
      </div>

      <button id="processBtn" class="btn-primary-modern" onclick="handleMediaProcess()">Proses Media</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.10.2/dist/quagga.js"></script>

<script>
  let selectedFile = null;

  // Focus ke input saat load
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

  // ✅ SCANNER - Barcode dengan keydown
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

  // ✅ MANUAL INPUT - Keydown untuk cari
  document.getElementById('manualIdentifier').addEventListener('keydown', function(event) {
    if(event.key === 'Enter'){
      event.preventDefault();
      cariDataManual();
    }
  });

  // Update info dengan badge
  function updateInfo(identifier){
    fetch(`/admin/absen/get-user/${identifier}`)
      .then(res=>res.json())
      .then(data=>{
        if(data.nama){
          let typeLabel = '', typeIdentifier = '';
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
          Swal.fire({icon:'success', title:'Berhasil!', text:body.message || 'Absen tersimpan.'})
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

  // ✅ MEDIA UPLOAD - Handle file dan gambar
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
      // PROSES GAMBAR BARCODE
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
      // PROSES FILE (EXCEL/CSV)
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
          Swal.fire({icon:'success', title:'Berhasil!', text:body.message || 'Absen tersimpan.'})
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
