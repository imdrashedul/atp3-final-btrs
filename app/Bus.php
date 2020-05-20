<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'operatorid', 'name', 'registration', 'seats_column', 'seats_row'
    ];

    protected $primaryKey = 'id';
    protected $table = 'buses';

    public function operator()
    {
        return $this->belongsTo('App\User','operatorid');
    }
}
