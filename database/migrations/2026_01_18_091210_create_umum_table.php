<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umum', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->text('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nohp')->nullable();
            $table->string('foto')->nullable(); // ✅ OPTIONAL
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umum');
    }
};
