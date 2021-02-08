<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'minPriceIncrement', 'currency', 'name'];
    protected $appends = ['average15day'];

    //15-minute charts
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
        }
        else
        {
            $this->attributes['average15day'] = 'nothing';
        }

        return $this->attributes['average15day'];
    }

    public function emaDayIndicator()
    {
        return $this->hasOne('App\EmaDayIndicator');
    }

    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }
}
