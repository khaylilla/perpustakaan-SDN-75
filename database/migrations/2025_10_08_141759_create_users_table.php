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
            $table->id();
            $table->string('nama');
            $table->string('nisn')->unique();
            $table->string('asal_sekolah')->nullable();
            $table->string('kelas')->nullable();
            $table->string('foto')->nullable(); // ✅ BOLEH KOSONG
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
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
