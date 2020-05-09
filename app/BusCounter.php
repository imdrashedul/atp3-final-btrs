<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusCounter extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'operatorid', 'name', 'location'
    ];
    
    protected $primaryKey = 'id';
    protected $table = 'buscounters';

    public function operator()
    {
        return $this->belongsTo('App\User','operatorid');
    }

}
