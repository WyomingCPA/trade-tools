<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Stock;
use App\Candle;

class Order extends Model
{
    protected $fillable = ['figi', 'current_price', 'note', 'strategy_name', 'status', 'quantity', 'created_at', 'strategy_id'];
    protected $appends = ['stop-order-count', 'name-instrument', 'max-change-price-after-order'];

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

    public function getMaxChangePriceAfterOrderAttribute()
    {
        $order_time = Carbon::parse($this->created_at);

        $stock_id = Stock::where('figi', $this->figi)->first()->id;

        $candles = Candle::where('tools_id', '=', $stock_id)->where('tools_type', '=', 'stock')->where('interval', '=', '5min')
            ->where('time', '>=', Carbon::create($order_time->year, $order_time->month, $order_time->day, $order_time->hour-3, $order_time->minute))
            ->orderBy('time', 'asc')->limit(6)->get();

        $list_max = [];
        foreach ($candles as $item) {
            $list_max [] = $item->close;
        }

        $max = 0;
        $percentChange = '';
        if (!empty($list_max))
        {
            $max = max($list_max);
            $percentChange = (1 - $this->current_price / $max) * 100;
        }
       
        return "$max ($percentChange%)";
    }
}
