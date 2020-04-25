<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    public $timestamps = false;
    protected $table = 'users_permission';
    protected $fillable = [
        'userid', 'permissionid'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'userid');
    }

    public function permission()
    {
        return $this->belongsTo('App\RolePermission', 'permissionid');
    }
}
