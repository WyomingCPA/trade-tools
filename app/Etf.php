<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etf extends Model
{
    protected $fillable = ['figi', 'ticker', 'isin', 'faceValue', 'minPriceIncrement', 'currency', 'name'];
}
