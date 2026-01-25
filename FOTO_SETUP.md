# 📸 SETUP FOTO PROFIL - FIX FINAL

## ✅ Yang Sudah Dikerjakan

### 1. Struktur Folder (SUDAH DIBUAT)
```
storage/app/public/foto/          ← Backend storage (backup)
public/storage/foto/              ← Browser bisa akses (PENTING!)
```

### 2. Controller Update (ProfileController.php)
**Flow baru:**
1. Upload foto dari form
2. Simpan ke `storage/app/public/foto` (backup)
3. Copy ke `public/storage/foto` (agar muncul di UI)
4. Simpan nama file ke database
5. Saat delete, hapus dari kedua lokasi

**Kode yang diupdate:**
```php
// Simpan foto baru dengan nama unik
$file = $request->file('foto');
$namaFoto = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

// 1. Simpan ke storage/app/public/foto (untuk backup)
$file->storeAs('public/foto', $namaFoto);

// 2. Copy ke public/storage/foto (agar langsung muncul di browser)
copy(
    storage_path('app/public/foto/' . $namaFoto),
    public_path('storage/foto/' . $namaFoto)
);

// Masukkan nama file ke objek user
$user->foto = $namaFoto;
```

### 3. Views Update

**profile.blade.php** ✅
```blade
@if($user->foto)
    <img src="/storage/foto/{{ $user->foto }}?v={{ time() }}" 
         alt="Foto Profil" class="profile-img">
@else
    <img src="{{ asset('default.jpg') }}" alt="Foto Default" class="profile-img">
@endif
```

**navbar.blade.php** ✅
```blade
@if ($foto)
    <img src="/storage/foto/{{ $foto }}?v={{ time() }}" width="40" height="40"
         class="rounded-circle border border-dark me-2" onerror="this.style.display='none'">
    @if (!file_exists(public_path('storage/foto/'.$foto)))
        <i class="bi bi-person-circle fs-3 me-2"></i>
    @endif
@else
    <i class="bi bi-person-circle fs-3 me-2"></i>
@endif
```

## 🔄 Flow Foto Profil

### SISWA (via User Model)
```
Profile Update → Controller → 
  ✅ Storage: storage/app/public/foto/
  ✅ Public:  public/storage/foto/
  ✅ DB:      user.foto = "timestamp_id.jpg"
```

### GURU (via Guru Model)
```
Profile Update → Controller → 
  ✅ Storage: storage/app/public/foto/
  ✅ Public:  public/storage/foto/
  ✅ DB:      guru.foto = "timestamp_id.jpg"
```

### UMUM (via Umum Model)
```
Profile Update → Controller → 
  ✅ Storage: storage/app/public/foto/
  ✅ Public:  public/storage/foto/
  ✅ DB:      umum.foto = "timestamp_id.jpg"
```

## 🧪 Testing Checklist

- [ ] Upload foto sebagai SISWA → cek profile page
- [ ] Upload foto sebagai GURU → cek profile page
- [ ] Upload foto sebagai UMUM → cek profile page
- [ ] Cek navbar menampilkan foto
- [ ] Edit foto → foto lama terhapus
- [ ] Test di browser: `http://localhost/storage/foto/NAMA_FILE.jpg`

## 🔗 URL yang Digunakan

**Profile Page:**
```
/storage/foto/{{ $user->foto }}
→ Resolve ke: public/storage/foto/{{ $user->foto }}
```

**Navbar:**
```
/storage/foto/{{ $foto }}
→ Resolve ke: public/storage/foto/{{ $foto }}
```

## ⚠️ Catatan Penting

1. **Cache Buster:** Gunakan `?v={{ time() }}` agar foto update langsung terlihat
2. **Error Handling:** Navbar pakai `onerror` untuk fallback ke icon jika foto tidak ada
3. **Symlink:** Tidak perlu symlink di Windows, langsung copy file
4. **Storage Path:** SELALU gunakan `/storage/foto/` bukan `storage/app/public`

## 📁 Backup Strategy

Foto disimpan di 2 tempat:
- **storage/app/public/foto/** → Backup (tidak langsung diakses)
- **public/storage/foto/** → Production (browser akses)

Jika production terhapus, bisa restore dari backup.

---

**Update: 21 January 2026**
