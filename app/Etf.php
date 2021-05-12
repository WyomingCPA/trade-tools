<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Etf extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];
    protected $appends = ['cci_hour', 'ema_hour', 'ema_day', 'rsi_hour', 'rsi_day', 'cci_day', 'aim'];

    public function getAimAttribute()
    {
        $list_aim = '';
        foreach ($this->aims as $item)
        {
            $list_aim  .= $item->aim_name . "\n";
        }
        return $list_aim;
    }
    public function getCciHourAttribute()
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
        if (!$cci)
        {
            return 0;
        }
        $last_element = end($cci);
        if ($last_element)
        {
            return $last_element;
        }
        else
        {
            return 0;
        }
    }

    public function getCciDayAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')
            ->where('interval', '=', 'day')->where('time', '>=', Carbon::now()->subMonths(7)->startOfDay())->orderBy('time', 'asc')->get();

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
        if (!$cci)
        {
            return 0;
        }
        $last_element = end($cci);
        if ($last_element)
        {
            return $last_element;
        }
        else
        {
            return 0;
        }
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

    public function getEmaDayAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')
            ->where('interval', '=', 'day')->where('time', '>=', Carbon::now()->subMonths(7)->startOfDay())->orderBy('time', 'asc')->pluck('close')->toArray();
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

    public function getRsiHourAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')->where('interval', '=', 'hour')
            ->where('created_at', '>=', Carbon::now()->subMonths(7)->startOfDay())->orderBy('time', 'asc')->get();

        $rsi_data = [];
        $rsi_raw = [];
        $key_time = [];
        $key_time_rsi = [];
        foreach ($candles as $item) {
            $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
            if (!array_key_exists($timestamp, $key_time)) {
                $rsi_raw['close'][] = $item->close;
                $rsi_raw['time'][] = $timestamp;
                $key_time_rsi[$timestamp] = $timestamp;
            }
        }
        if (array_key_exists('close', $rsi_raw)) {
            $rsi = trader_rsi($rsi_raw['close'], 14);
            if (!$rsi)
            {
                return 0;
            }
            foreach ($rsi as $key => $value) {
                $time = $rsi_raw['time'][$key];
                $rsi_data[] = [$time, $value];
            }
        }
        $rsi = array_pop($rsi_data);
        if (isset($rsi[1]))
        {
            return $rsi[1];
        }
        else
        {
            return 0;
        }
        
    }

    public function getRsiDayAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'etf')->where('interval', '=', 'day')
            ->where('created_at', '>=', Carbon::now()->subDays(20)->startOfDay())->orderBy('time', 'asc')->get();

        $rsi_data = [];
        $rsi_raw = [];
        $key_time = [];
        $key_time_rsi = [];
        foreach ($candles as $item) {
            $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
            if (!array_key_exists($timestamp, $key_time)) {
                $rsi_raw['close'][] = $item->close;
                $rsi_raw['time'][] = $timestamp;
                $key_time_rsi[$timestamp] = $timestamp;
            }
        }
        if (array_key_exists('close', $rsi_raw)) {
            $rsi = trader_rsi($rsi_raw['close'], 14);
            if (!$rsi)
            {
                return 0;
            }
            foreach ($rsi as $key => $value) {
                $time = $rsi_raw['time'][$key];
                $rsi_data[] = [$time, $value];
            }
        }
        $rsi = array_pop($rsi_data);
        if (isset($rsi[1]))
        {
            return $rsi[1];
        }
        else
        {
            return 0;
        }   
    }
    public function aims()
    {
        return $this->belongsToMany('App\AimEtf', 'aim-etf_etf', 'aim-etf_id', 'etf_id')->withTimestamps();
    }
}
