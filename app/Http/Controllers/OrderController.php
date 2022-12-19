<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\User;
use App\Candle;
use App\Order;
use App\Stock;
use App\StopOrder;
use App\OrderSpot;
use App\StatusScript;
use App\ConsoleLog;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $objects = Order::where('current_price', '!=', 0)->where('strategy_id', '=', 0)->orderByDesc('created_at');
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
            $objects->where('figi', 'like', '%' . $figi['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function today(Request $request)
    {
        $objects = Order::where('current_price', '!=', 0)->where('strategy_id', '=', 0)->whereDate('created_at', '=', Carbon::today())->orderByDesc('created_at');
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
            $objects->where('figi', 'like', '%' . $figi['searchTerm'] . '%');
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

    public function spotOrders(Request $request)
    {
        $id = $request->route('id');
        $objects = OrderSpot::where('order_id', '=', $id)->orderByDesc('created_at');
        return response()->json([
            'spots'  => $objects->get()->toArray(),
        ]);
    }

    public function spotDetail(Request $request)
    {
        $id = $request->route('id');
        $model = OrderSpot::find($id);
        $data = json_decode($model->data, true);
        
        return response()->json([
            'status' => true,
            'type' => $model->type,
            'data' => array_slice($data, -10),
        ], 200);
    }

    public function setSuccess(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        $models = Order::whereIn('id', $select)->update(['status' => 'success']);
        return response()->json([
            'status' => true,
        ], 200);
    }

    public function setFail(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        $models = Order::whereIn('id', $select)->update(['status' => 'fail']);
        return response()->json([
            'status' => true,
        ], 200);
    }

    public function setNothing(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        $models = Order::whereIn('id', $select)->update(['status' => 'nothing']);
        return response()->json([
            'status' => true,
        ], 200);
    }
    
    public function chartOrders(Request $request)
    {
        $id = $request->route('id');
        $order = Order::find($id);
        $order_time = Carbon::parse($order->created_at);

        $stock_id = Stock::where('figi', $order->figi)->first()->id;

        $mystring = $order->strategy_name;
        $time_frame   = '15min';
        $pos = strpos($mystring, $time_frame);
        if ($pos === false) {
            $time_frame = '5min';
        } else {
            $time_frame = '15min';
        }

        $candles = Candle::where('tools_id', '=', $stock_id)->where('tools_type', '=', 'stock')->where('interval', '=', $time_frame)
            ->where('time', '>=', Carbon::create($order_time->year, $order_time->month, $order_time->day, 0))
            ->where('time', '<=', Carbon::create($order_time->year, $order_time->month, $order_time->day, 24))->orderBy('time', 'asc')->get();

        $list = [];
        $rsi_data = [];
        $rsi_raw = [];
        $key_time = [];
        $orders = [];
        $key_time_rsi = [];

        foreach ($candles as $item) {
            $timestamp = str_pad(Carbon::parse($item->time)->addHours(3)->timestamp, 13, "0");
            if (!array_key_exists($timestamp, $key_time)) {
                $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                $key_time[$timestamp] = $timestamp;
                $rsi_raw['close'][] = $item->close;
                $rsi_raw['time'][] = $timestamp;
                $key_time_rsi[$timestamp] = $timestamp;   
            }
        }
        if (array_key_exists('close', $rsi_raw)) {
            $rsi = trader_rsi($rsi_raw['close'], 14);
            if ($rsi != false) {
                foreach ($rsi as $key => $value) {
                    $time = $rsi_raw['time'][$key];
                    $rsi_data[] = [$time, $value];
                }
            }
        }

        $start_period = Carbon::parse($order_time)->subHour(10);
        $end_period =  Carbon::parse($order_time)->addHours(6);
        $end = str_pad($end_period->timestamp, 13, "0");
        $start = str_pad($start_period->timestamp, 13, "0");
        $order_indicators_time = str_pad($order_time->timestamp, 13, "0");
        //$orders [] = [$order_indicators_time, "Bay. Price: " . $order->current_price, 1, "#34a853", 0.55];
        $orders[] = [$order_indicators_time, 1, $order->current_price,  "Bay. Price: " . $order->current_price];
        //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
        //Делаем время начала и конец в timestamp

        //Получаем список стоп-ордеоров, разделяя их на стоплосс и тайкпрофит
        $stop_orders_list = $order->stops;
        $list_take_profit1 = [];
        $list_take_profit2 = [];
        $list_stop_orders1 = [];
        $list_stop_orders2 = [];
        foreach ($stop_orders_list as $item) {
            if ($item->expiration_type == 'StopOrderType.STOP_ORDER_TYPE_STOP_LOSS') {
                for ($i = 1; $i <= 2; $i++) {
                    if ($i == 1) {
                        $list_stop_orders1 = [$start, $item->price];
                    } else {
                        $list_stop_orders2 = [$end, $item->price];
                    }
                }
            } else {
                for ($i = 1; $i <= 2; $i++) {
                    if ($i == 1) {
                        $list_take_profit1 = [$start, $item->price];
                    } else {
                        $list_take_profit2 = [$end, $item->price];
                    }
                }
            }
        }
        

        return response([
            'candles' => $list,
            'order' => $orders,
            'rsi_data' => $rsi_data,
            'list_take_profit1' => $list_take_profit1,
            'list_take_profit2' => $list_take_profit2,
            'list_stop_orders1' => $list_stop_orders1,
            'list_stop_orders2' => $list_stop_orders2,
        ], 200);
    }
    public function chart15Orders(Request $request)
    {
        $id = $request->route('id');
        $order = Order::find($id);
        $order_time = Carbon::parse($order->created_at);

        $stock_id = Stock::where('figi', $order->figi)->first()->id;

        $mystring = $order->strategy_name;
        $time_frame   = '15min';
        $pos = strpos($mystring, $time_frame);
        if ($pos === false) {
            $time_frame = '5min';
        } else {
            $time_frame = '15min';
        }

        $candles = Candle::where('tools_id', '=', $stock_id)->where('tools_type', '=', 'stock')->where('interval', '=', '15min')
            ->where('time', '>=', Carbon::create($order_time->year, $order_time->month, $order_time->day, 0))
            ->where('time', '<=', Carbon::create($order_time->year, $order_time->month, $order_time->day, 24))->orderBy('time', 'asc')->get();

        $list = [];
        $rsi_data = [];
        $rsi_raw = [];
        $key_time = [];
        $orders = [];
        $key_time_rsi = [];

        foreach ($candles as $item) {
            $timestamp = str_pad(Carbon::parse($item->time)->addHours(3)->timestamp, 13, "0");
            if (!array_key_exists($timestamp, $key_time)) {
                $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                $key_time[$timestamp] = $timestamp;
                $rsi_raw['close'][] = $item->close;
                $rsi_raw['time'][] = $timestamp;
                $key_time_rsi[$timestamp] = $timestamp;   
            }
        }
        if (array_key_exists('close', $rsi_raw)) {
            $rsi = trader_rsi($rsi_raw['close'], 14);
            if ($rsi != false) {
                foreach ($rsi as $key => $value) {
                    $time = $rsi_raw['time'][$key];
                    $rsi_data[] = [$time, $value];
                }
            }
        }

        $start_period = Carbon::parse($order_time)->subHour(10);
        $end_period =  Carbon::parse($order_time)->addHours(6);
        $end = str_pad($end_period->timestamp, 13, "0");
        $start = str_pad($start_period->timestamp, 13, "0");
        $order_indicators_time = str_pad($order_time->timestamp, 13, "0");
        //$orders [] = [$order_indicators_time, "Bay. Price: " . $order->current_price, 1, "#34a853", 0.55];
        $orders[] = [$order_indicators_time, 1, $order->current_price,  "Bay. Price: " . $order->current_price];
        //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
        //Делаем время начала и конец в timestamp

        //Получаем список стоп-ордеоров, разделяя их на стоплосс и тайкпрофит
        $stop_orders_list = $order->stops;
        $list_take_profit1 = [];
        $list_take_profit2 = [];
        $list_stop_orders1 = [];
        $list_stop_orders2 = [];
        foreach ($stop_orders_list as $item) {
            if ($item->expiration_type == 'StopOrderType.STOP_ORDER_TYPE_STOP_LOSS') {
                for ($i = 1; $i <= 2; $i++) {
                    if ($i == 1) {
                        $list_stop_orders1 = [$start, $item->price];
                    } else {
                        $list_stop_orders2 = [$end, $item->price];
                    }
                }
            } else {
                for ($i = 1; $i <= 2; $i++) {
                    if ($i == 1) {
                        $list_take_profit1 = [$start, $item->price];
                    } else {
                        $list_take_profit2 = [$end, $item->price];
                    }
                }
            }
        }
        

        return response([
            'candles' => $list,
            'order' => $orders,
            'rsi_data' => $rsi_data,
            'list_take_profit1' => $list_take_profit1,
            'list_take_profit2' => $list_take_profit2,
            'list_stop_orders1' => $list_stop_orders1,
            'list_stop_orders2' => $list_stop_orders2,
        ], 200);
    }

    public function store(Request $request)
    {
        //$post = $request->post();
        $model = Order::create($request->all());

        return response()->json([
            'status' => true,
            'order_id' => $model->id,
        ], 200);
    }

    public function addSpot(Request $request)
    {
        $model = OrderSpot::create([
            'order_id' => $request->order_id,
            'type' => $request->type,
            'data' => $request->value,
        ]);
        return response([
            'status' => true,
        ], 200);        
    }

    public function checkScript(Request $request)
    {
        $post  = $request->post();
        foreach ($post as $name => $value)
        {

            $model = StatusScript::updateOrCreate(
                ['name' => $name,],
                [
                    'is_run' => (int)$value,
                ]
            );
        } 
        return response([
            'status' => true,
        ], 200);  
    }

    public function delete(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        $stop_orders = StopOrder::whereIn('order_id', $select)->delete();
        $spot_orders = OrderSpot::whereIn('order_id', $select)->delete();
        $orders = Order::whereIn('id', $select)->delete();

        return response([
            'status' => true,
        ], 200);
    }

    public function deleteAll(Request $request)
    {
        $order = Order::truncate();
        $stop_order = StopOrder::truncate();
        $spots = OrderSpot::truncate();

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function startScriptSuperTrend5min(Request $request)
    {     
        $command = '';
        $output=  '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

        } else {
            $command = escapeshellcmd("/var/www/trader/env/bin/python /var/www/trader/SuperTrend_MACD_TimeFrame_5min.py");
            $output = shell_exec($command);
        }

        return response([
            'status' => true,
            'output' => $output,
        ], 200);
    }

    public function stopScriptSuperTrend5min(Request $request)
    {     
        $command = '';
        $output=  '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

        } else {
            $command = escapeshellcmd("/var/www/trader/env/bin/python /var/www/trader/tools/bash_start_script/kill_process.py -sn SuperTrend_MACD_TimeFrame_5min");
            $output = shell_exec($command);
        }

        return response([
            'status' => true,
            'output' => $output,
        ], 200);
    }

    public function startScriptCheckStopOrder(Request $request)
    {     
        $command = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

        } else {
            $command = escapeshellcmd("/var/www/trader/env/bin/python /var/www/trader/check_stop_order.py");
            $output = shell_exec($command);
        }

        return response([
            'status' => true,
        ], 200);
    }

    public function lastError(Request $request)
    {
        $search = 'error';
        $errors = ConsoleLog::where('message', 'like', '%' . $search . '%')->orderByDesc('created_at');
        return response([
            'errors' => $errors->get()->toArray(),
            'status' => true,
        ], 200);
    }
}
