<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Calculator;
class CalculatorController extends Controller
{
    public function store(Request $request)
    {
        $data = response()->json($request->all());
        $model = Calculator::create([
            'type' => $request->type,
            'data' => $data->content(),
        ]);
        return response([
            'status' => true,
        ], 200);        
    }

    public function stockAverage(Request $request)
    {
        $calc_models = Calculator::where('type', 'stock_average')->latest()->take(5)->get();
        return response([
            'calc_models' => $calc_models,
            'status' => true,
        ], 200); 
    }
}
