<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmaDayIndicator extends Model
{
    protected $fillable = ['stock_id', 'action', 'send_telegramm'];

    protected $table = 'ema_day_indicator';

    public function stock(){
        return $this->belongsTo('App\Stock');
    }
}
