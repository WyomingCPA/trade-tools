<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Balance;

class Check extends Model
{
    protected $fillable = ['name', 'type', 'limit', 'precent'];
    protected $appends = ['balances', 'difference_point', 'difference_limit'];

    public function balances()
    {
        return $this->hasMany(Balance::class);
    }
    public function getBalancesAttribute()
    {
        $model = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->first();
        return $model;
    }
    //Список участников на разный промежуток времени
    public function getDifferencePointAttribute()
    {
        $last_balance = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(0)->take(2)->get()->first()->balance ?? 0;
        $previous_balance = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(1)->take(2)->get()->first()->balance ?? 0;
        $count = $last_balance - $previous_balance;
        return $count;
    }
    //Разница между лимитом и остатком
    public function getDifferenceLimitAttribute()
    {
        $last_balance = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(0)->take(2)->get()->first()->balance ?? 0;
        $limit = $this->limit ?? 0;
        $differ = $last_balance - $limit;
        return $differ;
    } 
}
