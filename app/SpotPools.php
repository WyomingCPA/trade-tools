<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpotPools extends Model
{
    protected $fillable = [
        'total_balances', 'count_pools', 
    ];
}
