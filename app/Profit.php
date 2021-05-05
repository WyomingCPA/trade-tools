<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Profit extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['order_id', 'figi', 'price', 'take_profit', 'stop_loss',];


}
