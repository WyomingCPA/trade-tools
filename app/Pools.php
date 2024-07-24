<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pools extends Model
{
    protected $table = 'pools';
    protected $fillable = [
        'balances', 'min', 'max', 'cryptocurrencies_id', 'uncollected', 'name',
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
    public static function boot() {

        parent::boot();

        static::created(function($item) {
            //Получаем последний слепок пулов и сравниваем с текущим значением
            
        });
    }  
}
