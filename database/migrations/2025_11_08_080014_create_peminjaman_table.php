<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');            // Nama anggota
            $table->string('npm');             // Barcode / NPM anggota
            $table->string('judul_buku');      // Judul buku
            $table->string('nomor_buku');      // Nomor buku
            $table->date('tanggal_pinjam')->default(now());
            $table->date('tanggal_kembali')->nullable(); // Bisa diisi saat pengembalian
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('peminjaman');
    }
};
