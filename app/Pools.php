<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pools extends Model
{
    protected $table = 'pools';
    protected $fillable = [
        'balances',
        'min',
        'max',
        'cryptocurrencies_id',
        'uncollected',
        'name',
    ];
    protected $appends = ['last_price', ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
    //Аттрибут получает последнюю свечу закрытия
    public function getLastPriceAttribute()
    {
        $last_candle = Candle::where('tools_id', '=', $this->cryptocurrencies_id)->where('tools_type', '=', 'coins')
            ->where('interval', '=', '1h')->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'desc')->first();
        return $last_candle;
    }
    public static function boot()
    {

        parent::boot();

        static::created(function ($item) {
            //Получаем последний слепок пулов и сравниваем с текущим значением

        });
    }
}
