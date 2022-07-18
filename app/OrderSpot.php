<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class OrderSpot extends Model
{
    protected $table = 'spot_order';

    protected $fillable = ['order_id', 'type', 'data'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
}
