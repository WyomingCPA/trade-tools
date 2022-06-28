<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Order;

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

        return response([
            'today_open_orders' => $today_open_orders,
            'week_open_orders' => $week_open_orders,
            'month_open_orders' => $month_open_orders,
        ], 200);
    }
}
