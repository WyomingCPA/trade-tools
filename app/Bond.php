<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];

    public function lastPrice()
    {
        return $this->hasMany(Candle::class, 'tools_id');
    }

    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }
}
