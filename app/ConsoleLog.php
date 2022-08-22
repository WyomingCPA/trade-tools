<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ConsoleLog extends Model
{
    protected $fillable = ['type', 'message', 'data'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('H:i:s j F Y');
    }
}
