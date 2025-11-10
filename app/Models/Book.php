<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

   protected $fillable = [
        'cover',
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'deskripsi',
        'nomor_buku',
        'rak',
        'status',
        'jumlah',
    ];

}
