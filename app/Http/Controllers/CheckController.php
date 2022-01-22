<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Check;
use App\Balance;

class CheckController extends Controller
{
    public function index(Request $request)
    {
        $test = '';
        return response([
            'checks' => Check::all()->toArray(),
        ], 200);
    }
    public function store(Request $request)
    {
        $model = Check::create([
            'name' => $request->name_check,
            'type' => $request->type_check,
            'limit' => $request->limit_check,
            'precent' => $request->precent_check,
        ]);
        return response([
            'status' => true,
        ], 200);
    }
    public function setBalance(Request $request)
    {
        $id_check = $request->id_check;
        $set_balance = $request->set_balance;
        $model = Balance::create([
            'check_id' => $id_check,
            'balance' => $set_balance,
        ]);
        //group_id
        //count_subscriber

        return response([
            'status' => true,
        ], 200);
    }

    public function delete(Request $request)
    {
        $model = Check::find($request->id_check);
        $model->delete();

        return response([
            'status' => true,
        ], 200);
    }
}
