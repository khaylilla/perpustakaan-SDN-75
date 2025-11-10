<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel users.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key otomatis
            $table->string('email')->unique(); // Email unik
            $table->string('password'); // Password (nanti disimpan dalam hash)
            $table->string('nama'); // Nama lengkap
            $table->string('npm'); // Nomor pokok mahasiswa
            $table->string('alamat'); // Alamat lengkap
            $table->date('tgl_lahir'); // Tanggal lahir
            $table->string('nohp'); // Nomor HP
            $table->string('foto')->nullable(); // Path foto, opsional
            $table->timestamps(); // Kolom created_at & updated_at
        });
    }

    /**
     * Batalkan migrasi (hapus tabel users jika rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
