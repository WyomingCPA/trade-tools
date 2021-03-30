<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BondDetail extends Model
{
    protected $fillable = ['bond_id','current_yield', 'maturity_yield', 
                            'maturity_date', 'maturity_calloption', 
                            'date_calloption', 'paymant_date',
                            'date_payment_coupon', 'accumulated_coupon',
                            'amount_coupon', 'nominal', 'period_payment'
                        ];
}
