<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Stock;
use App\Order;

class TestStrategy extends Model
{
    protected $fillable = ['start_period', 'end_period','figi', 'strategy_name', 'time_frame', 'balance', 'status', 'is_completed'];
    protected $appends = ['name-from-figi', 'count-orders'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }

    public function getNameFromFigiAttribute()
    {
        $name_tools = Stock::where('figi', $this->figi)->first()->name;
        return $name_tools;
    }

    public function getCountOrdersAttribute()
    {
        $countOrders = Order::where('strategy_id', $this->id)->count();
        return $countOrders;
    }
}
