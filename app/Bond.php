<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Candle;
use App\BondDetail;

class Bond extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];

    protected $appends = ['last_price', 'nominal', 'current_yield'];

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

    public function detail()
    {
        return $this->hasOne('App\BondDetail', 'bond_id');
    }

    public function getCurrentYieldAttribute()
    {
        $model = BondDetail::firstWhere('bond_id', $this->id);
        return $model->current_yield ?? 'No data';
    }
    public function scopeSearch($query, $q)
    {
        if ($q == null) return $query;
        return $query
            ->where('name', 'LIKE', "%{$q}%")
            ->orWhere('isin', 'LIKE', "%{$q}%");
    }
}
