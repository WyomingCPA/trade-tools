<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Candle;
class CandleController extends Controller
{
    public function deleteAll()
    {
        $model = Candle::truncate();
        return response()->json([
            'status' => true,
        ], 200);
    }
}
