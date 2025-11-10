<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('cover')->nullable();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->string('tahun_terbit')->nullable();
            $table->string('kategori')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('nomor_buku')->unique();
            $table->string('rak')->nullable();
            $table->string('barcode')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
