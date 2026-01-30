<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus kolom npm jika ada
            if (Schema::hasColumn('peminjaman', 'npm')) {
                $table->dropColumn('npm');
            }
            
            // Tambah kolom nisn, nip, email
            if (!Schema::hasColumn('peminjaman', 'nisn')) {
                $table->string('nisn')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('peminjaman', 'nip')) {
                $table->string('nip')->nullable()->after('nisn');
            }
            if (!Schema::hasColumn('peminjaman', 'email')) {
                $table->string('email')->nullable()->after('nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus kolom nisn, nip, email
            if (Schema::hasColumn('peminjaman', 'nisn')) {
                $table->dropColumn('nisn');
            }
            if (Schema::hasColumn('peminjaman', 'nip')) {
                $table->dropColumn('nip');
            }
            if (Schema::hasColumn('peminjaman', 'email')) {
                $table->dropColumn('email');
            }
            
            // Kembalikan kolom npm
            $table->string('npm')->nullable();
        });
    }
};
