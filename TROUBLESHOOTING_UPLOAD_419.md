# 🔴 TROUBLESHOOTING: PAGE EXPIRED (419) SAAT UPLOAD FILE

## ✅ STATUS CURRENT PROJECT

| Item | Status | Keterangan |
|------|--------|-----------|
| PHP `upload_max_filesize` | ✅ **2G** | Sudah optimal (default: 2M) |
| PHP `post_max_size` | ✅ **2G** | Sudah optimal (default: 8M) |
| PHP `max_execution_time` | ✅ **36000s** | Sudah optimal (default: 30s) |
| PHP `memory_limit` | ✅ **512M** | Sudah optimal (default: 128M) |
| `@csrf` token | ✅ **Present** | Valid di semua form |
| `enctype="multipart/form-data"` | ✅ **Present** | Valid di modal |
| `cover.*` validation | ✅ **image\|mimes:jpeg,png,jpg\|max:2048** | Sudah benar |
| `SESSION_DRIVER` | ✅ **file** | Sudah benar di .env |

---

## 🚀 JIKA MASIH DAPAT ERROR 419

### Debug Step 1: Upload File Kecil Test
```
✅ Buka admin page
✅ Coba upload 1 foto < 500KB
```

**Hasil yang diharapkan:**
- `OK` = Upload works, lanjut step 2
- `419 Page Expired` = Ada setting yang perlu direset

---

### Debug Step 2: Cek Laravel Cache
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:cache
```

**Penjelasan:**
- Cache CSRF token yang lama mungkin masih tersimpan
- Clearing cache akan reset session

---

### Debug Step 3: Cek Log Errors
```bash
tail -f storage/logs/laravel.log
```

**Cari pesan:**
- `"CSRF token mismatch"` → Session expired
- `"The file ... exceeds your upload_max_filesize"` → File size
- `"Request entity too large"` → post_max_size

---

### Debug Step 4: Check Network Tab (Browser)
```
🔧 Buka DevTools (F12)
🔧 Tab Network
🔧 Coba upload foto
🔧 Cari request POST ke /store
```

**Cek Response:**
- `Status 419` = CSRF / Session issue
- `Status 413` = File too large
- `Status 200` = Success

---

## ⚙️ JIKA TETAP ERROR: RESTART EVERYTHING

### Step 1: Stop Laragon Completely
```
Laragon tray icon → Stop All
Tunggu 5 detik
```

### Step 2: Clear Browser Cache
```
Chrome: Ctrl+Shift+Delete → Clear all time
```

### Step 3: Start Laragon Again
```
Laragon tray icon → Start All
Tunggu hingga status "Running"
```

### Step 4: Access aplikasi fresh
```
Clear cookies jika perlu: F12 → Application → Clear all cookies
Buka http://127.0.0.1:8000 fresh
```

---

## 🛠️ KONFIGURASI PHP YANG SUDAH BENAR

File: `C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.ini`

```ini
; === UPLOAD SETTINGS ===
upload_max_filesize = 2G          ✅
post_max_size = 2G                ✅
max_execution_time = 36000        ✅
max_input_time = 60               ✅

; === MEMORY ===
memory_limit = 512M               ✅
```

---

## 🔍 JIKA MASIH GAGAL: TRACE REQUEST FLOW

### Controller: `BookController.php`
```php
public function store(Request $request) {
    $request->validate([
        'cover.*' => 'image|mimes:jpeg,png,jpg|max:2048', ✅
    ]);
    // ... rest of code
}
```

### View: `datakoleksi.blade.php`
```blade
<form ... enctype="multipart/form-data">  ✅
    @csrf                                   ✅
    <input name="cover[]" multiple accept="image/*">  ✅
</form>
```

### Environment: `.env`
```env
SESSION_DRIVER=file               ✅
```

---

## 📝 SUMMARY

**Penyebab 419 Error saat upload:**
1. ❌ CSRF token expired/tidak cocok (jarang untuk upload form)
2. ❌ Session sudah expired (jarang kalau SESSION_DRIVER=file)
3. ❌ File size melebihi limit **← PALING SERING**
4. ❌ PHP config tidak reload setelah perubahan

**Solusi yang sudah diterapkan:**
- ✅ PHP settings optimal
- ✅ Validasi di controller correct
- ✅ Blade form lengkap + accept="image/*"
- ✅ Session driver file

**Jika masih error:**
1. Cek browser console (Network tab)
2. Cek Laravel log: `storage/logs/laravel.log`
3. Restart Laravel cache: `php artisan optimize:clear`
4. Restart Laragon total

---

*Last updated: January 25, 2026*
