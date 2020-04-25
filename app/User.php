<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'email', 'password', 'name', 'company', 'operatorid', 'roleid', 'validated', 'registered'
    ];

    protected $hidden = [
        'password',
    ];


    public function role()
    {
        return $this->belongsTo('App\Role', 'roleid');
    }

    public function operator()
    {
        return $this->belongsTo('App\User', 'operatorid');
    }

    public function permissions()
    {
        return $this->hasMany('App\UserPermission', 'userid');
    }
}
