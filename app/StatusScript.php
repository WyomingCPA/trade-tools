<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class StatusScript extends Model
{
    protected $table = 'status_script';
    
    protected $fillable = ['name', 'is_run'];

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour(3)->format('H:i:s j F Y');
    }
}
