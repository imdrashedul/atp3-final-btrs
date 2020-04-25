<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    public $timestamps = false;
    protected $table = 'roles_permission';
    protected $fillable = [
        'roleid', 'permission', 'details'
    ];

    public function role()
    {
        return $this->belongsTo('App\Role', 'roleid');
    }

}
