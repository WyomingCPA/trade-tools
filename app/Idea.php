<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Idea extends Model
{
    protected $fillable = ['figi', 'name', 'action', 'min_period', 'aim_price', 'data', 'description', 'status'];
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
}
