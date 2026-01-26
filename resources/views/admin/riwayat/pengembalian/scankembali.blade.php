@extends('admin.layout')

@section('page-title', 'Scan Pengembalian Buku')

@section('content')
<style>
  .info-boxes {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
  }

  .info-box {
    background: linear-gradient(135deg, #f7931e, #ffa94d);
    color: white;
    border-radius: 16px;
    width: 300px;
    padding: 20px 24px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    transition: transform 0.2s ease;
  }

  .info-box:hover { transform: translateY(-3px); }
  .info-box i { font-size: 32px; opacity: 0.7; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin: 3px 0 0 0; }

  .scan-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    padding: 40px;
    max-width: 700px;
    margin: 0 auto;
  }

  .scan-box {
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eee;
  }

  .scan-box:last-child { border-bottom: none; }

  .scan-box h5 {
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
  }

  .scan-input {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    flex-wrap: wrap;
  }

  .scan-input input {
    flex: 1;
    min-width: 250px;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.2s;
  }

  .scan-input input:focus {
    outline: none;
    border-color: #f7931e;
    box-shadow: 0 0 8px rgba(247, 147, 30, 0.2);
  }

  .info-scan {
    font-size: 14px;
    color: #666;
    margin-top: 8px;
  }

  .info-scan span {
    font-weight: 700;
    color: #f7931e;
  }

  .submit-btn {
    text-align: center;
    margin-top: 30px;
  }

  .submit-btn button {
    background: linear-gradient(135deg, #f7931e, #ffa94d);
    border: none;
    color: white;
    font-weight: 700;
    padding: 14px 50px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(247, 147, 30, 0.3);
  }

  .submit-btn button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(247, 147, 30, 0.4);
  }

  .submit-btn button:active {
    transform: translateY(0);
  }
</style>

<div class="container-fluid">
  {{-- INFO BOXES --}}
  <div class="info-boxes">
    <a href="{{ route('admin.riwayat.pengembalian.scankembali') }}" class="info-box">
      <div class="info-box-content">
        <h5>Scan Pengembalian</h5>
        <p>Scan barcode anggota & buku</p>
      </div>
      <i class="bi bi-upc-scan"></i>
    </a>

    <a href="{{ route('admin.riwayat.pengembalian.pengembalian') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Pengembalian</h5>
        <p>Daftar buku yang dikembalikan</p>
      </div>
      <i class="bi bi-book-half"></i>
    </a>
  </div>

  {{-- SCAN FORM --}}
  <div class="scan-container">
    {{-- Scan NPM Anggota --}}
    <div class="scan-box">
      <h5>Scan/Input NPM Anggota</h5>
      <div class="scan-input">
        <input type="text" id="npm" placeholder="Scan kartu atau ketik NPM..." onkeydown="handleEnterNpm(event)">
      </div>
      <div class="info-scan" id="infoNpm">Nama: <span id="namaNpm">-</span></div>
    </div>

    {{-- Scan Nomor Buku --}}
    <div class="scan-box">
      <h5>Scan/Input Nomor Buku</h5>
      <div class="scan-input">
        <input type="text" id="nomorBuku" placeholder="Scan barcode atau ketik nomor buku..." onkeydown="handleEnterBuku(event)">
      </div>
      <div class="info-scan" id="infoBuku">Judul: <span id="judulBuku">-</span>, Stok: <span id="stokBuku">-</span></div>
    </div>

    {{-- Submit Button --}}
    <div class="submit-btn">
      <button onclick="prosesPengembalian()">Simpan & Proses Pengembalian</button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function handleEnterNpm(e) {
  if (e.key === 'Enter') {
    updateInfo('npm');
  }
}

function handleEnterBuku(e) {
  if (e.key === 'Enter') {
    updateInfo('buku');
  }
}

function updateInfo(type) {
  if (type === 'npm') {
    const npm = document.getElementById('npm').value.trim();
    if (!npm) return;

    fetch(`/admin/riwayat/peminjaman/get-user/${npm}`)
      .then(res => res.json())
      .then(data => {
        if (data.nama) {
          document.getElementById('namaNpm').textContent = data.nama;
        } else {
          document.getElementById('namaNpm').textContent = 'Tidak ditemukan';
        }
      })
      .catch(() => {
        document.getElementById('namaNpm').textContent = 'Error';
      });
  }

  if (type === 'buku') {
    const nomorBuku = document.getElementById('nomorBuku').value.trim();
    if (!nomorBuku) return;

    fetch(`/admin/riwayat/peminjaman/get-book/${nomorBuku}`)
      .then(res => res.json())
      .then(data => {
        if (data.judul) {
          document.getElementById('judulBuku').textContent = data.judul;
          document.getElementById('stokBuku').textContent = data.jumlah ?? 0;
        } else {
          document.getElementById('judulBuku').textContent = 'Tidak ditemukan';
          document.getElementById('stokBuku').textContent = '-';
        }
      })
      .catch(() => {
        document.getElementById('judulBuku').textContent = 'Error';
        document.getElementById('stokBuku').textContent = '-';
      });
  }
}

function prosesPengembalian() {
  const npm = document.getElementById('npm').value.trim();
  const nomorBuku = document.getElementById('nomorBuku').value.trim();

  if (!npm || !nomorBuku) {
    Swal.fire({
      icon: 'warning',
      title: 'Data Tidak Lengkap',
      text: 'Pastikan NPM dan Nomor Buku telah diisi.',
      confirmButtonColor: '#f7931e'
    });
    return;
  }

  // ✨ CEK TIPE USER untuk menentukan apakah perlu popup
  fetch(`/admin/riwayat/peminjaman/get-user/${npm}`)
    .then(res => res.json())
    .then(data => {
      const peminjamTipe = data.peminjam_tipe || 'umum';
      
      if (peminjamTipe === 'guru') {
        // GURU: Popup input jumlah
        Swal.fire({
          title: 'Jumlah Buku Dikembalikan',
          input: 'number',
          inputValue: 1,
          inputAttributes: {
            min: 1,
            step: 1
          },
          showCancelButton: true,
          confirmButtonColor: '#f7931e',
          confirmButtonText: 'Proses',
          cancelButtonText: 'Batal',
          preConfirm: (jumlah) => {
            if (!jumlah || jumlah < 1) {
              Swal.showValidationMessage('Jumlah harus minimal 1');
            }
            return jumlah;
          }
        }).then((result) => {
          if (result.isConfirmed) {
            submitPengembalianAdmin(npm, nomorBuku, result.value);
          }
        });
      } else {
        // SISWA/UMUM: Auto 1 buku, langsung proses tanpa popup
        submitPengembalianAdmin(npm, nomorBuku, 1);
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

function submitPengembalianAdmin(npm, nomorBuku, jumlah) {
  fetch("{{ route('admin.riwayat.pengembalian.proses') }}", {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    body: JSON.stringify({
      npm: npm,
      nomor_buku: nomorBuku,
      jumlah: jumlah
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.message && data.message.includes('berhasil')) {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: data.message,
        confirmButtonColor: '#f7931e'
      }).then(() => {
        // Reset form
        document.getElementById('npm').value = '';
        document.getElementById('nomorBuku').value = '';
        document.getElementById('namaNpm').textContent = '-';
        document.getElementById('judulBuku').textContent = '-';
        document.getElementById('stokBuku').textContent = '-';
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: data.message || 'Gagal memproses pengembalian.',
        confirmButtonColor: '#f7931e'
      });
    }
  })
  .catch(err => {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Gagal mengirim data ke server.',
      confirmButtonColor: '#f7931e'
    });
  });
}
</script>

@endsection
