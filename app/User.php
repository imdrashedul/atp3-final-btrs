<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'email', 'password', 'name', 'company', 'operator', 'role', 'validated', 'registered'
    ];

    protected $hidden = [
        'password',
    ];
}
