<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class StopOrder extends Model
{
    protected $table = 'stop-orders';

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }

}
