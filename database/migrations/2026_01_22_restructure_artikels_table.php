<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('artikels', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('artikels', 'link')) {
                $table->dropColumn('link');
            }
        });

        Schema::table('artikels', function (Blueprint $table) {
            // Tambah kolom baru
            $table->enum('kategori', ['Informasi/Pengumuman', 'Berita', 'Artikel'])->after('judul');
            $table->string('subjudul')->nullable()->after('kategori');
            $table->longText('isi')->after('subjudul');
            $table->string('foto')->nullable()->after('isi');
            $table->string('link')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'subjudul', 'isi', 'foto', 'link']);
            $table->text('deskripsi')->after('judul');
        });
    }
};
