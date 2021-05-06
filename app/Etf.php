<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Etf extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];

    public function getCciAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')
            ->where('interval', '=', 'hour')->where('time', '>=', Carbon::now()->subDay(5)->startOfDay())->orderBy('time', 'asc')->get();

        $highs = [];
        $lows = [];
        $closes = [];

        $time_period = 21;

        foreach ($models as $item) {
            $highs[] = $item->hight;
            $lows[] = $item->low;
            $closes[] = $item->close;
        }

        $cci = trader_cci($highs, $lows, $closes, $time_period);
        return $cci[0];
    }

    public function getEmaHourAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')
            ->where('interval', '=', 'hour')->where('time', '>=', Carbon::now()->subDay(5)->startOfDay())->orderBy('time', 'asc')->pluck('close')->toArray();
        $prices = [];
        foreach ($models as $close) {
            $prices[] = $close;
        }

        if (empty($prices)) {
            return 'nothing';
        }

        $ema5 = trader_ema($prices, 5);
        $ema8 = trader_ema($prices, 8);

        //$ema13 = trader_ema($prices, 13);
        if ($ema5 != false && $ema8 != false) {
            $current_5 = array_pop($ema5);
            $current_8 = array_pop($ema8);

            $previous_5 = array_pop($ema5);
            $previous_8 = array_pop($ema8);

            $action = '';
            if ($current_5 > $current_8 && $previous_5 > $previous_8) {
                $action =  'buy';
            } elseif ($current_5 < $current_8 && $previous_5 < $previous_8) {
                $action = 'sell';
            } else {
                $action = 'nothing';
            }
            return $action;
        } else {
            return 'nothing';
        }
    }
}
