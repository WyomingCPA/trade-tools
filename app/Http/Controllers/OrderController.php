<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Order;
use App\StopOrder;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $objects = Order::where('current_price', '!=', 0)->orderByDesc('created_at');
        $count = $objects->count();
        $sort       = $request->get('sort');
        $direction  = $request->get('direction');
        $figi       = $request->get('figi');
        $created_by = $request->get('created_by');
        $type       = $request->get('type');
        $limit      = 20;
        $page       = (int)$request->get('page');
        $created_at = $request->get('created_at');
    
        if ($figi !== null) {
            $objects->where('name', 'like', '%' . $figi['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }
    public function stopOrders(Request $request)
    {
        $id = $request->route('id');
        $objects = StopOrder::where('order_id', '=', $id)->orderByDesc('created_at');
        return response()->json([
            'stocks'  => $objects->get()->toArray(),
        ]);
    }
}
