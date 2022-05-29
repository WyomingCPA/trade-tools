<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = ['figi', 'current_price', 'quantity', 'created_at'];
    protected $appends = ['stop-order-count'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }

    public function getStopOrderCountAttribute()
    {
        $stop_orders = StopOrder::where('order_id', '=', $this->id)->count();
        return $stop_orders;
    }
}
