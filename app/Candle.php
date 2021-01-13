<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candle extends Model
{
    protected $fillable = ['tools_id', 'open', 'close', 'high', 'low', 'volume', 'time', 'interval'];
}
