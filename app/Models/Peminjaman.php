<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'nama',
        'nisn',
        'nip',
        'email',
        'judul_buku',
        'nomor_buku',
        'jumlah',
        'jumlah_kembali',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];
}
