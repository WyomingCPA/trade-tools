<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = ['order_id', 'figi', 'price', 'take_profit', 'stop_loss',];


}
