<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Stock extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'minPriceIncrement', 'currency', 'name', 'min_precent', 'take_profit'];
    protected $appends = ['average15day', 'adx', 'min_precent', 'take_profit', 'cci_hour', 'rsi_hour', 'rsi_day', 'cci_day', 'is_portfolio'];

    public function getIsPortfolioAttribute()
    {
        $model = Portfel::where('figi', '=', $this->figi)->where('tools_type', '=', 'stock')
            ->where('created_at', '>=', Carbon::now()->subDay(3)->startOfDay())->orderBy('created_at', 'asc')->count();
        if ($model != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getAdxAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)
            ->where('tools_type', '=', 'stock')
            ->where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->get();
        $highs = [];
        $low = [];
        $close = [];

        foreach ($models as $item) {
            $highs[] = $item->high;
            $low[] = $item->low;
            $close[] = $item->close;
        }
        //$time_period = floor(count($highs) / 2);
        $time_period = 4;
        $adx = trader_adx($highs, $low, $close, $time_period);

        if ($adx != false) {
            $adxValue = array_pop($adx);
            $action = 'HOLD';
            if ($adxValue > 50) {
                $action = 'BUY';
            } elseif ($adxValue < 20) {
                $action = 'SELL';
            }

            $this->attributes['adx'] = $action;
            return $this->attributes['adx'];
        }
    }
    public function getTestavgAttribute()
    {
        $from = Carbon::parse("2021-04-16 17:10")->subHours(3)->toDateTimeString();
        $to = Carbon::parse("2021-04-16 17:50")->subHours(3)->toDateTimeString();
        $models = Candle::where('tools_id', '=', 833)->where('tools_type', '=', 'stock')
            ->where('interval', '=', '5min')
            ->whereBetween('time', [$from, $to])
            //->where('close', 233.48)
            ->orderBy('time', 'asc')->pluck('close')->toArray();

        $prices = [];
        foreach ($models as $close) {
            $prices[] = $close;
        }

        $ema5 = trader_ema($prices, 5);
        $ema8 = trader_ema($prices, 8);
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
        return 'test';
    }
    //5-minute charts
    public function getAverage15dayAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
            ->where('interval', '=', '5min')->where('time', '>=', Carbon::now()->subHours(12)->startOfDay())->orderBy('time', 'asc')->pluck('close')->toArray();
        $prices = [];
        foreach ($models as $close) {
            $prices[] = $close;
        }

        if (empty($prices)) {
            return $this->attributes['average15day'] = 'nothing';
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
            $this->attributes['average15day'] = $action;
        } else {
            $this->attributes['average15day'] = 'nothing';
        }

        return $this->attributes['average15day'];
    }

    public function getCciAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
            ->where('interval', '=', '5min')->where('time', '>=', Carbon::now()->subDay(1)->startOfDay())->orderBy('time', 'asc')->get();

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

    public function getRsiAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
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
            return null;
        }
        
    }

    public function emaDayIndicator()
    {
        return $this->hasOne('App\EmaDayIndicator');
    }
    //Аттрибут расчитывает минимальную цену за период
    //И возвращает процентную разницу
    public function getMinPrecentAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
            ->where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->pluck('close')->toArray();

        if (empty($models)) {
            $this->attributes['min_precent'] = 'No data';
            return $this->attributes['min_precent'];
        }

        $min_price = min($models);
        $price =  $this->last_price;

        $oldprice = $min_price;
        $newprice = $price;

        $res = round((($oldprice - $newprice) / $oldprice) * 100, 2);

        $this->attributes['min_precent'] = $res;

        return $this->attributes['min_precent'];
    }

    public function getTakeProfitAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
            ->where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->pluck('close')->toArray();

        if (empty($models)) {
            $this->attributes['take_profit'] = 'No data';
            return $this->attributes['take_profit'];
        }

        $max_price = max($models);
        $price =  $this->last_price;

        $oldprice = $max_price;
        $newprice = $price;

        $res = round((($oldprice - $newprice) / $oldprice) * 100, 2);
        $this->attributes['take_profit'] = $res;
        return $this->attributes['take_profit'];
    }

    public function getLastPriceAttribute()
    {
        $model = Candle::where('tools_id', '=', $this->id)->orderBy('created_at', 'asc')->orderBy('time', 'asc')->get();
        return $this->attributes['last_price'] = $model->last()->close ?? 0;
    }

    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }

    public function getCciHourAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
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
        if (!$cci) {
            return 0;
        }
        $last_element = end($cci);
        if ($last_element) {
            return $last_element;
        } else {
            return 0;
        }
    }

    public function getCciDayAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
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
        if (!$cci) {
            return 0;
        }
        $last_element = end($cci);
        if ($last_element) {
            return $last_element;
        } else {
            return 0;
        }
    }
    public function getRsiHourAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')->where('interval', '=', 'hour')
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
            if (!$rsi) {
                return 0;
            }
            foreach ($rsi as $key => $value) {
                $time = $rsi_raw['time'][$key];
                $rsi_data[] = [$time, $value];
            }
        }
        $rsi = array_pop($rsi_data);
        if (isset($rsi[1])) {
            return $rsi[1];
        } else {
            return 0;
        }
    }

    public function getRsiDayAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')->where('interval', '=', 'day')
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
            if (!$rsi) {
                return 0;
            }
            foreach ($rsi as $key => $value) {
                $time = $rsi_raw['time'][$key];
                $rsi_data[] = [$time, $value];
            }
        }
        $rsi = array_pop($rsi_data);
        if (isset($rsi[1])) {
            return $rsi[1];
        } else {
            return 0;
        }
    }
}
