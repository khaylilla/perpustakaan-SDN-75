<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable
{
    protected $table = 'guru';

    protected $fillable = [
        'nama', 'email', 'nip', 'alamat',
        'tgl_lahir', 'nohp', 'foto', 'password'
    ];

    protected $hidden = ['password'];
}