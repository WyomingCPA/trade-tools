<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Cryptocurrency extends Model
{
    protected $fillable = [
        'symbol',
    ];
    protected $appends = ['rsi_hour', 'pools_range'];

    public function getRsiHourAttribute()
    {
        $candles = Candle::where('tools_id', '=', $this->id)->where('tools_type', '=', 'coins')->where('interval', '=', '1h')
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
    public function getPoolsRangeAttribute()
    {
        $pool = Pools::where('cryptocurrencies_id', $this->id)->first();
        return $pool;
    }
}
