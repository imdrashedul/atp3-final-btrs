<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusSchedule extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'busid', 'departure', 'arrival', 'route', 'fare', 'boardingid'
    ];

    protected $primaryKey = 'id';
    protected $table = 'busschedules';

    public function bus()
    {
        return $this->belongsTo('App\Bus','busid');
    }

    public function boarding()
    {
        return $this->belongsTo('App\BusCounter','boardingid');
    }
}
