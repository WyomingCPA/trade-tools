<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Stock;

class Order extends Model
{
    protected $fillable = ['figi', 'current_price', 'quantity', 'created_at'];
    protected $appends = ['stop-order-count', 'name-instrument'];

    public function stops()
    {
        return $this->hasMany(StopOrder::class, 'order_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }

    public function getStopOrderCountAttribute()
    {
        $stop_orders = StopOrder::where('order_id', '=', $this->id)->count();
        return $stop_orders;
    }
    
    public function getNameInstrumentAttribute()
    {
        $name = Stock::where('figi', $this->figi)->first()->name;
        return $name;
    }
}
