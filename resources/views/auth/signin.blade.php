<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up | Perpustakaan Digital SDN 75</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
*{box-sizing:border-box}
body{
  margin:0;
  min-height:100vh;
  font-family:'Inter',sans-serif;
  background:#eef2ff;
  overflow-x:hidden;
}

/* ===== BACKGROUND SMOOTH ===== */
.bg-layer{
  position:fixed;
  inset:0;
  /* background image (place your image at public/ft.jpg) with soft tint overlays */
  background:
    url('{{ asset('ft.jpg') }}') center/cover no-repeat,
    radial-gradient(circle at 15% 20%, rgba(247,147,30,.16), transparent 55%),
    radial-gradient(circle at 85% 35%, rgba(27,42,110,.18), transparent 60%),
    linear-gradient(135deg, rgba(238,242,255,0.6), rgba(255,247,237,0.5));
  background-blend-mode:overlay,overlay,screen,normal;
  z-index:0;
}

/* subtle floating blobs */
.shape{
  position:absolute;
  border-radius:50%;
  filter:blur(60px);
  opacity:.35;
  animation:float 20s ease-in-out infinite alternate;
}
.shape.orange{
  width:240px;height:240px;
  background:#f7931e;
  top:12%;left:8%;
}
.shape.blue{
  width:280px;height:280px;
  background:#1b2a6e;
  bottom:10%;right:10%;
  animation-delay:6s;
}

@keyframes float{
  from{transform:translateY(0)}
  to{transform:translateY(-35px)}
}

/* ===== CARD ===== */
.card-signup{
  position:relative;
  z-index:2;
  width:100%;
  max-width:430px;
  /* translucent card so background shows through slightly */
  background: rgba(255,255,255,0.86);
  backdrop-filter: blur(6px) saturate(120%);
  -webkit-backdrop-filter: blur(6px) saturate(120%);
  border-radius:28px;
  padding:40px;
  margin:70px auto;
  box-shadow:
    0 30px 60px rgba(27,42,110,.14),
    0 10px 25px rgba(247,147,30,.09);
  border: 1px solid rgba(27,42,110,0.06);
  animation:cardIn .8s cubic-bezier(.25,.9,.4,1);
}

@keyframes cardIn{
  from{opacity:0;transform:translateY(40px)}
  to{opacity:1;transform:none}
}

/* HEADER */
.logo{width:52px}
h4{
  font-weight:700;
  color:#1b2a6e;
}

/* ROLE */
.role-toggle{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:10px;
  margin-bottom:18px;
}
.role-toggle input{display:none}
.role-toggle label{
  padding:9px;
  border-radius:14px;
  border:2px solid #1b2a6e;
  font-size:.8rem;
  font-weight:600;
  cursor:pointer;
  color:#1b2a6e;
  transition:.3s;
  text-align:center;
}
.role-toggle input:checked + label{
  background:#1b2a6e;
  color:white;
}

/* FORM */
label{
  font-size:.75rem;
  font-weight:600;
  text-align:left;
  color:#1b2a6e;
}
.form-control{
  border-radius:14px;
  padding:10px 14px;
  font-size:.82rem;
}
.form-control:focus{
  border-color:#f7931e;
  box-shadow:0 0 0 .18rem rgba(247,147,30,.22);
}

/* smooth field animation */
.form-section{
  animation:fadeSlide .35s ease;
}
@keyframes fadeSlide{
  from{opacity:0;transform:translateY(6px)}
  to{opacity:1;transform:none}
}

/* PASSWORD */
.toggle-password{
  position:absolute;
  right:12px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  color:#1b2a6e;
}

/* UPLOAD */
.upload-box{
  border:2px dashed rgba(247,147,30,.6);
  border-radius:16px;
  padding:14px;
  font-size:.8rem;
  transition:.3s;
}
.upload-box:hover{
  background:#fff7ed;
}

/* BUTTON */
.btn-signup{
  width:100%;
  border-radius:16px;
  padding:11px;
  background:#1b2a6e;
  color:white;
  font-weight:600;
  border:none;
  transition:.3s;
}
.btn-signup:hover{
  background:#142257;
  transform:translateY(-1px);
  box-shadow:0 10px 22px rgba(27,42,110,.28);
}

@media(max-width:768px){
  .card-signup{margin:40px 15px}
}
</style>
</head>

<body>

<div class="bg-layer"></div>
<div class="shape orange"></div>
<div class="shape blue"></div>

<div class="card-signup">

<div class="text-center mb-3">
  <img src="{{ asset('unib.jpg') }}" class="logo mb-2">
  <h4>Sign up for free</h4>
  <small class="text-muted">Buat akun perpustakaan digital</small>
</div>

<form action="{{ route('signin.submit') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="role-toggle">
  <input type="radio" name="role" id="guru" value="guru" checked>
  <label for="guru">Guru</label>
  <input type="radio" name="role" id="siswa" value="siswa">
  <label for="siswa">Siswa</label>
  <input type="radio" name="role" id="umum" value="umum">
  <label for="umum">Umum</label>
</div>

<div id="field-email" class="form-section">
  <label>Email</label>
  <input type="email" name="email" class="form-control mb-2">
</div>

<label>Nama Lengkap</label>
<input type="text" name="nama" class="form-control mb-2" required>

<div id="field-npm" class="form-section" style="display:none">
  <label>NIS</label>
  <input type="text" name="nis" class="form-control mb-2">
</div>

<div id="field-nip" class="form-section">
  <label>NIP</label>
  <input type="text" name="nip" class="form-control mb-2">
</div>

<div id="field-asal" class="form-section" style="display:none">
  <label>Asal Sekolah</label>
  <input type="text" name="asal_sekolah" class="form-control mb-2">
</div>

<div id="field-alamat" class="form-section">
  <label>Alamat</label>
  <input type="text" name="alamat" class="form-control mb-2">
</div>

<div id="field-tgl" class="form-section">
  <label>Tanggal Lahir</label>
  <input type="date" name="tgl_lahir" class="form-control mb-2">
</div>

<div id="field-nohp" class="form-section">
  <label>No. HP</label>
  <input type="text" name="nohp" class="form-control mb-2">
</div>

<label>Foto</label>
<div class="upload-box mb-3">
  <input type="file" name="foto" accept="image/*" required>
</div>

<!-- PASSWORD DI BAWAH -->
<label>Password</label>
<div class="position-relative mb-2">
  <input type="password" name="password" id="password" class="form-control" required>
  <span class="toggle-password" onclick="togglePassword('password')">
    <i class="fa fa-eye" id="password-icon"></i>
  </span>
</div>

<label>Konfirmasi Password</label>
<div class="position-relative mb-3">
  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
  <span class="toggle-password" onclick="togglePassword('password_confirmation')">
    <i class="fa fa-eye" id="password_confirmation-icon"></i>
  </span>
</div>

<button class="btn-signup">Create Account</button>

<small class="d-block text-center mt-3">
  Sudah punya akun? <a href="{{ url('/login') }}">Login</a>
</small>

</form>
</div>

<script>
function togglePassword(id){
  const input=document.getElementById(id);
  const icon=document.getElementById(id+'-icon');
  input.type = input.type === "password" ? "text" : "password";
  icon.classList.toggle('fa-eye');
  icon.classList.toggle('fa-eye-slash');
}
</script>

</body>
</html>
