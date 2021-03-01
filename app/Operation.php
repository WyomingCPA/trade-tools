<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = ['operation_id', 'figi', 'status', 'payment', 'price', 'commission', 
                           'currency', 'instrumentType', 'date',  'operationType'
                        ];
}
