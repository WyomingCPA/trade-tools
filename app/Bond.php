<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Candle;

class Bond extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];

    protected $appends = ['last_price', 'nominal'];

    public function lastPrice()
    {
        return $this->hasMany(Candle::class, 'tools_id');
    }
    //$item->lastPrice->last()->close
    public function getLastPriceAttribute()
    {
        $model = Candle::where('tools_id', '=', $this->id)->get();
        return $this->attributes['last_price'] = $model->last()->close ?? 0;
    }

    public function getNominalAttribute()
    {
        return $this->attributes['nominal'] = $this->faceValue ?? 0;

    }

    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }
}
