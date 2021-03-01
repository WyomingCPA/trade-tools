<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['figi', 'login_date', 'release_date', 'profit', 'сommission'];

}
