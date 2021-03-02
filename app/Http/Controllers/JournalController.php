<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Order;
use Carbon\Carbon;

class JournalController extends Controller
{
    public function index()
    {
        $profit_day = 0;
        $profit_month = 0;

        $profit_d = Order::where('release_date', '>=', Carbon::now()->subDays(1)->startOfDay())->orderBy('release_date', 'desc')->pluck('profit');
        $profit_m = Order::where('release_date', '>=', Carbon::now()->subDays(30)->startOfDay())->orderBy('release_date', 'desc')->pluck('profit');

        foreach ($profit_d as $item)
        {
            $profit_day += $item;
        }

        foreach ($profit_m as $item) {
            $profit_month += $item;
        }

        $orders = Order::where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay())->orderBy('release_date', 'desc')->get();
        return view('journal.index', compact('orders', 'profit_day', 'profit_month'));
    }

    public function calculate(Request $request)
    {
        $rows = $request->post('selRows');
        $сommission = 0;
        $profit = 0;
        $login_date = '';
        $release_date = '';
        $buy = 0;
        $sell = 0;
        foreach ($rows as $item)
        {
            if ($item["operationType"] == "BrokerCommission")
            {
                $сommission = $сommission + (float)$item["payment"];
            }
            if ($item["operationType"] == "Buy")
            {
                $login_date = $item["date"];
                $buy = $item["payment"];
            }
            if ($item["operationType"] == "Sell")
            {
                $release_date = $item["date"];
                $sell = $item["payment"];
                $figi = $item["figi"];
            }
        }

        $profit = (float)$sell + (float)$buy + $сommission;
        $order = Order::create([
            'figi' => $figi,
            'login_date' => $login_date,
            'release_date' => $release_date,
            'profit' => $profit,
            'сommission' => $сommission,
        ]);

        return response()->json([
            'cod' => 200
        ], 200);
    }
    
    public function delete(Request $request)
    {
        $rows = $request->post('selRows');
        foreach ($rows as $item)
        {
            $model = Order::find($item['id']);
            $model->delete();
        }
    }
}
