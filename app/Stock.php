<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Stock extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'minPriceIncrement', 'currency', 'name', 'min_precent', 'take_profit'];
    protected $appends = ['average15day', 'adx', 'min_precent', 'take_profit'];

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
    //5-minute charts
    public function getAverage15dayAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')->pluck('close')->toArray();
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
            if ($current_5 > $current_8 && $previous_5 < $previous_8) {
                $action =  'buy';
            } elseif ($current_5 < $current_8 && $previous_5 > $previous_8) {
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

    public function emaDayIndicator()
    {
        return $this->hasOne('App\EmaDayIndicator');
    }
    //Аттрибут расчитывает минимальную цену за период
    //И возвращает процентную разницу
    public function getMinPrecentAttribute()
    {
        $models = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'stock')
            ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())->pluck('close')->toArray();

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
            ->where('created_at', '>=', Carbon::now()->subDays(2)->startOfDay())->pluck('close')->toArray();

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
        $model = Candle::where('tools_id', '=', $this->id)->get();
        return $this->attributes['last_price'] = $model->last()->close ?? 0;
    }

    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }
}
