<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AimEtf extends Model
{
    protected $table = 'aim-etf';

    protected $fillable = [
        'aim_name', 'event_detail',
    ];

    public function etfs(){
        return $this->belongsToMany('App\Etf', 'aim-etf_etf', 'etf_id', 'aim-etf_id')->withTimestamps();
    }
}
