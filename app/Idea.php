<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use App\Stock;

class Idea extends Model
{
    protected $fillable = ['figi', 'name', 'action', 'min_period', 'aim_price', 'data', 'description', 'status'];
    protected $appends = ['name-instrument', 'ticker'];
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }

    public function getNameInstrumentAttribute()
    {
        $name = Stock::where('figi', $this->figi)->first()->name ?? 'Нет';
        return $name;
    }

    public function getTickerAttribute()
    {
        $ticker = Stock::where('figi', $this->figi)->first()->ticker ?? 'Нет';
        return $ticker;
    }
}
