<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Order;
use App\Candle;
use App\StatusScript;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today_open_orders = Order::where('current_price', '!=', 0)
            ->where('strategy_id', '=', 0)
            ->where('created_at', '>=', Carbon::today()->toDateString())
            ->orderByDesc('created_at')->count();

        $week_open_orders = Order::where('current_price', '!=', 0)
            ->where('strategy_id', '=', 0)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderByDesc('created_at')->count();

        $month_open_orders = Order::where('current_price', '!=', 0)
            ->where('strategy_id', '=', 0)
            ->whereMonth('created_at', date('m'))
            ->orderByDesc('created_at')->count();

        $count_all_candles = Candle::all()->count(); 
        $candles_last_ago = Candle::where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->orderByDesc('created_at')->first();
        if ($candles_last_ago != null)
        {
            $candles_lastg_ago_text = $candles_last_ago->created_at->diffForHumans();
        }  

        $count_all_orders = Order::all()->count();
        $orders_last_ago = Order::where('created_at', '>=', Carbon::now()->subMonth(1)->startOfDay())->orderByDesc('created_at')->first();
        if ($orders_last_ago != null)
        {
            $orders_lastg_ago_text = $orders_last_ago->created_at;
        }
        $all_scripts = StatusScript::all(); 

        return response([
            'today_open_orders' => $today_open_orders,
            'week_open_orders' => $week_open_orders,
            'month_open_orders' => $month_open_orders,
            'count_all_candles' => $count_all_candles,
            'candles_last_ago' => $candles_lastg_ago_text ?? 'No',
            'count_all_orders' => $count_all_orders,
            'orders_last_ago' => $orders_lastg_ago_text ?? 'No',
            'all_scripts' => $all_scripts,
        ], 200);
    }
}
