<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Log;

use TelegramBot\Api\BotApi;

use App\Stock;
use App\Candle;
use Exception;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = ['figi', 'order_id', 'direction', 'order_type', 'current_price', 'note', 'strategy_name', 'status', 'quantity', 'created_at', 'strategy_id'];
    protected $appends = ['stop-order-count', 'spot-order-count', 'name-instrument', 'max-change-price-after-order'];

    public static function boot() {

        parent::boot();

        static::created(function($item) {

            if ($item->strategy_id == 0 && $item->strategy_name != 'SuperTrend_MACD_TimeFrame_5min_future_simple')
            {
                $messageText = "Позиция открыта $item->created_at \n";
                $messageText .= "Figi инструмента $item->figi \n";  
        
                $chatId = '-414528593';
        
                $bot = new BotApi(env('TELEGRAM_TOKEN'));
                $bot->sendMessage($chatId, $messageText, 'HTML');
            }
            if ($item->strategy_id == 0 && $item->strategy_name === 'SuperTrend_MACD_TimeFrame_5min_future_simple')
            {
                $future = Futures::where('figi', $item->figi)->first();
                $messageText = "Сигнал SuperTrend 5 min на $item->direction \n";

                $messageText .= "Название инструмента $future->name \n";

                $messageText .= "<a target='_blank' href='https://www.tinkoff.ru/invest/futures/{$future->ticker}'>{$future->name}</a>\n";  
        
                $chatId = '-607026497';
        
                $bot = new BotApi(env('TELEGRAM_TOKEN'));
                $bot->sendMessage($chatId, $messageText, 'HTML');
            }

        });
    }

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

    public function getSpotOrderCountAttribute()
    {
        $stop_orders = OrderSpot::where('order_id', '=', $this->id)->count();
        return $stop_orders;
    }

    public function getNameInstrumentAttribute()
    {
        $name = Stock::where('figi', $this->figi)->first()->name ?? Futures::where('figi', $this->figi)->first()->name;
        return $name;
    }

    public function getMaxChangePriceAfterOrderAttribute()
    {
        try {
            $order_time = Carbon::parse($this->created_at);

            $stock_id = Stock::where('figi', $this->figi)->first()->id ?? Futures::where('figi', $this->figi)->first()->id;
    
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
        catch(Exception $e)
        {
            return 0;
        }
    }
}
