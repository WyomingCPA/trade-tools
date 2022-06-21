<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TestStrategy extends Model
{
    protected $fillable = ['start_period', 'end_period','figi', 'strategy_name', 'time_frame', 'balance', 'status', 'is_completed'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
}
