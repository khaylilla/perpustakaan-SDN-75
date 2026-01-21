<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Umum extends Authenticatable
{
    protected $table = 'umum';

    protected $fillable = [
        'nama', 'email', 'alamat',
        'tgl_lahir', 'nohp', 'foto', 'password'
    ];

    protected $hidden = ['password'];
}
