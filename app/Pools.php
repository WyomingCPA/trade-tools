<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pools extends Model
{
    protected $table = 'pools';
    protected $fillable = [
        'balances', 'min', 'max', 'cryptocurrencies_id'
    ];
}
