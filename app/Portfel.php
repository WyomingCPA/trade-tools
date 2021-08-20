<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfel extends Model
{
    protected $fillable = ['tools_type', 'figi', 'ticker', 'isin', 'currency', 'name', 'lots', 'expectedYieldValue', 'averagePositionPrice'];
}
