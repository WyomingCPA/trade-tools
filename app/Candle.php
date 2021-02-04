<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candle extends Model
{
    protected $fillable = ['tools_id', 'tools_type', 'open', 'close', 'high', 'low', 'volume', 'time', 'interval'];
}
