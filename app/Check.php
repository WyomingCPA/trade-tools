<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Balance;

class Check extends Model
{
    protected $fillable = ['name', 'type', 'limit', 'precent'];
    protected $appends = ['balances', 'difference_point', 'difference_limit', 'precent_month_calculate', 'ratio'];

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
    //Рассчет процентов в месяц precent_month_calculate
    public function getPrecentMonthCalculateAttribute()
    {
        $month_precent = 0;
        if ($this->type === "credit")
        {
            $last_balance = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(0)->take(2)->get()->first()->balance ?? 0;
            $limit = $this->limit ?? 0;
            $duty = $limit - $last_balance;
            $year_precent = ($this->precent / 100) * $duty;
            $day_precent = $year_precent / 365;
            $month_precent = round($day_precent*30);

        }
        return $month_precent;
    }

    public function getRatioAttribute()
    {
        if ($this->type === "credit")
        {
            $last_balance = Balance::where('check_id', '=', $this->id)->orderBy('created_at', 'desc')->skip(0)->take(2)->get()->first()->balance ?? 0;
            $limit = $this->limit ?? 0;
            if ($limit != 0)
            {
                $divide_balance_limit = $last_balance / $limit;
                $ratio = $divide_balance_limit * 100;
                return $ratio;
            }
        }

        return null;
    }
}
