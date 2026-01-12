<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In - Sistem Informasi Perpustakaan</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    * { box-sizing: border-box; }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      font-family: 'Poppins', sans-serif;
      position: relative;
    }

    .background-img {
      position: fixed;
      inset: 0;
      background: url('{{ asset('FT.jpg') }}') center/cover no-repeat;
      opacity: 0.6;
      z-index: 0;
    }

    .triangle-left {
      position: fixed;
      inset: 0;
      width: 70%;
      background-color: #f7931e;
      clip-path: polygon(0 0, 65% 0, 85% 100%, 0 100%);
      z-index: 1;
    }

    .signin-box {
      z-index: 2;
      background: white;
      padding: 50px;
      border-radius: 15px;
      width: 650px;
      margin: 60px 0;
      box-shadow: 0 0 25px rgba(0,0,0,0.2);
    }

    .signin-box img {
      width: 90px;
      margin-bottom: 10px;
    }

    .btn-signin {
      background: #1b2a6e;
      color: white;
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      border: none;
    }

    .btn-signin:hover {
      background: #142257;
    }

    /* ===== ROLE TOGGLE ===== */
    .role-toggle {
      display: flex;
      gap: 10px;
      margin-bottom: 15px;
    }

    .role-toggle input {
      display: none;
    }

    .role-toggle label {
      flex: 1;
      padding: 10px;
      text-align: center;
      border-radius: 8px;
      border: 2px solid #1b2a6e;
      cursor: pointer;
      font-weight: 500;
      color: #1b2a6e;
      transition: .3s;
    }

    .role-toggle input:checked + label {
      background: #1b2a6e;
      color: white;
    }

    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
    }

    .upload-box {
      border: 2px dashed #ccc;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
    }

    @media (max-width: 768px) {
      .signin-box { width: 90%; }
      .triangle-left { width: 100%; clip-path: none; }
    }
  </style>
</head>

<body>

<div class="background-img"></div>
<div class="triangle-left"></div>

<div class="signin-box text-center">

  <img src="{{ asset('unib.jpg') }}">
  <h3 class="fw-bold text-primary">SIGN IN</h3>
  <p>Isi data di bawah ini</p>

<form action="{{ route('signin.submit') }}" method="POST" enctype="multipart/form-data">
@csrf

<!-- ROLE -->
<label class="text-start w-100 mb-2"><b>Daftar Sebagai</b></label>
<div class="role-toggle">
  <input type="radio" name="role" id="guru" value="guru" checked>
  <label for="guru">Guru</label>

  <input type="radio" name="role" id="siswa" value="siswa">
  <label for="siswa">Siswa</label>

  <input type="radio" name="role" id="umum" value="umum">
  <label for="umum">Umum</label>
</div>

<div id="field-email">
  <label class="text-start w-100"><b>Email</b></label>
  <input type="email" name="email" class="form-control mb-2">
</div>

<label class="text-start w-100"><b>Password</b></label>
<div class="position-relative mb-2">
  <input type="password" name="password" id="password" class="form-control" required>
  <span class="toggle-password" onclick="togglePassword('password')">
    <i class="fa fa-eye" id="password-icon"></i>
  </span>
</div>

<label class="text-start w-100"><b>Konfirmasi Password</b></label>
<div class="position-relative mb-2">
  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
  <span class="toggle-password" onclick="togglePassword('password_confirmation')">
    <i class="fa fa-eye" id="password_confirmation-icon"></i>
  </span>
</div>

<label class="text-start w-100"><b>Nama Lengkap</b></label>
<input type="text" name="nama" class="form-control mb-2" required>

<!-- NPM (NIS) -->
<div id="field-npm" style="display:none;">
  <label class="text-start w-100"><b>NIS</b></label>
  <input type="text" name="nis" class="form-control mb-2">
  </div>

<!-- NIP -->
<div id="field-nip">
  <label class="text-start w-100"><b>NIP</b></label>
  <input type="text" name="nip" class="form-control mb-2">
</div>

<!-- Asal Sekolah (untuk siswa) -->
<div id="field-asal" style="display:none;">
  <label class="text-start w-100"><b>Asal Sekolah</b></label>
  <input type="text" name="asal_sekolah" class="form-control mb-2">
</div>

<div id="field-alamat">
  <label class="text-start w-100"><b>Alamat</b></label>
  <input type="text" name="alamat" class="form-control mb-2">
</div>

<div id="field-tgl">
  <label class="text-start w-100"><b>Tanggal Lahir</b></label>
  <input type="date" name="tgl_lahir" class="form-control mb-2">
</div>

<div id="field-nohp">
  <label class="text-start w-100"><b>No. HP</b></label>
  <input type="text" name="nohp" class="form-control mb-2">
</div>

<label class="text-start w-100"><b>Foto</b></label>
<div class="upload-box mb-3">
  <input type="file" name="foto" accept="image/*" required>
</div>

<button class="btn-signin">Sign In</button>

<small class="d-block mt-3">
  Sudah punya akun? <a href="{{ url('/login') }}">Login</a>
</small>

</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function togglePassword(id) {
  const input = document.getElementById(id);
  const icon = document.getElementById(id + '-icon');
  if(input.type === "password") {
    input.type = "text";
    icon.classList.replace('fa-eye','fa-eye-slash');
  } else {
    input.type = "password";
    icon.classList.replace('fa-eye-slash','fa-eye');
  }
}

// ROLE LOGIC
const roles = document.querySelectorAll('input[name="role"]');
const npmField = document.getElementById('field-npm');
const nipField = document.getElementById('field-nip');
const asalField = document.getElementById('field-asal');
const emailField = document.getElementById('field-email');
const alamatField = document.getElementById('field-alamat');
const tglField = document.getElementById('field-tgl');
const nohpField = document.getElementById('field-nohp');

function setRequired(el, required) {
  if(!el) return;
  const inputs = el.querySelectorAll('input');
  inputs.forEach(i => { if(required) i.setAttribute('required','required'); else i.removeAttribute('required'); });
}

function switchRole(role) {
  if(role === 'guru') {
    nipField.style.display = 'block';
    npmField.style.display = 'none';
    asalField.style.display = 'none';
    emailField.style.display = 'block';
    alamatField.style.display = 'block';
    tglField.style.display = 'block';
    nohpField.style.display = 'block';

    setRequired(nipField, true);
    setRequired(npmField, false);
    setRequired(emailField, true);
    setRequired(alamatField, true);
    setRequired(tglField, true);
    setRequired(nohpField, true);
    setRequired(asalField, false);
  } else if(role === 'siswa') {
    nipField.style.display = 'none';
    npmField.style.display = 'block';
    asalField.style.display = 'block';
    emailField.style.display = 'none';
    alamatField.style.display = 'none';
    tglField.style.display = 'none';
    nohpField.style.display = 'none';

    setRequired(nipField, false);
    setRequired(npmField, true);
    setRequired(asalField, true);
    setRequired(emailField, false);
    setRequired(alamatField, false);
    setRequired(tglField, false);
    setRequired(nohpField, false);
  } else {
    // umum
    nipField.style.display = 'none';
    npmField.style.display = 'none';
    asalField.style.display = 'none';
    emailField.style.display = 'block';
    alamatField.style.display = 'block';
    tglField.style.display = 'block';
    nohpField.style.display = 'block';

    setRequired(nipField, false);
    setRequired(npmField, false);
    setRequired(asalField, false);
    setRequired(emailField, true);
    setRequired(alamatField, true);
    setRequired(tglField, true);
    setRequired(nohpField, true);
  }
}

roles.forEach(r => {
  r.addEventListener('change', () => switchRole(r.value));
});

// initialize based on checked role
const checked = document.querySelector('input[name="role"]:checked');
if(checked) switchRole(checked.value);
</script>

</body>
</html>
