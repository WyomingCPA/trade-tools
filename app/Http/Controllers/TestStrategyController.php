<?php

namespace App\Http\Controllers;

use \Datetime;
use \DatePeriod;
use \DateInterval;

use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


use App\User;
use App\Order;
use App\TestStrategy;

use App\Candle;
use App\Stock;

use \jamesRUS52\TinkoffInvest\TIClient;
use \jamesRUS52\TinkoffInvest\TISiteEnum;
use \jamesRUS52\TinkoffInvest\TICurrencyEnum;
use \jamesRUS52\TinkoffInvest\TIInstrument;
use \jamesRUS52\TinkoffInvest\TIPortfolio;
use \jamesRUS52\TinkoffInvest\TIOperationEnum;
use \jamesRUS52\TinkoffInvest\TIIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandleIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandle;
use \jamesRUS52\TinkoffInvest\TIOrderBook;
use \jamesRUS52\TinkoffInvest\TIInstrumentInfo;

class TestStrategyController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'models' => TestStrategy::all()->toArray(),
            'status' => true,
        ], 200);
    }
    public function create(Request $request)
    {
        $tools = Auth::user()->favoritesStock;

        return response([
            'tools' => $tools,
            'status' => true,
        ], 200);
    }

    public function store(Request $request)
    {
        /*
            start_date: this.startDate,
            end_date: this.endDate,
            figi: this.selectedFigi,
            time_frame: this.selectedTimeFrame,
            strategy_name: this.selectedStrategy,
        */
        $request->request->add(['balance' => 5000]);
        $validatedData = validator()->make($request->all(), [
            'start_period'         => 'required',
            'end_period'          => 'required',
            'figi'       => 'required',
            'time_frame'          => 'required',
            'strategy_name'   => 'required',
        ]);

        if ($validatedData->fails()) {
            return response([
                'status' => false,
            ], 200);
        }

        $model = TestStrategy::create($request->all());

        return response([
            'status' => true,
        ], 200);
    }

    public function getCandleTest(Request $request)
    {
        $rows = $request->post('selRows');
        $start_period = $rows['row']['start_period'];
        $end_period = $rows['row']['end_period'];
        $time_frame = $rows['row']['time_frame'];
        $figi = $rows['row']['figi'];

        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $period = new DatePeriod(
            new DateTime($start_period),
            new DateInterval('P1D'),
            new DateTime($end_period)
        );
        $stock = Stock::where('figi', '=', $figi)->first();

        foreach ($period as $key => $value) {
            $from = new DateTime($value->format('Y-m-d 10:i:00'));
            $to = new DateTime($value->format('Y-m-d 19:i:00'));
            try {
                $candles = $client->getHistoryCandles($figi, $from, $to, TIIntervalEnum::MIN5);
            } catch (\Exception $e) {
                echo $e->getMessage();
                continue;
            }
            foreach ($candles as $candle) {
                try {
                    $model = Candle::firstOrCreate(
                        ['tools_id' => $stock->id, 'tools_type' => 'stock', 'close' => $candle->getClose() ? $candle->getClose() : 0, 'time' => $candle->getTime()],
                        [
                            'tools_id' => $stock->id,
                            'tools_type' => 'stock',
                            'open' => $candle->getOpen() ? $candle->getOpen() : 0,
                            'close' => $candle->getClose() ? $candle->getClose() : 0,
                            'high' => $candle->getHigh() ? $candle->getHigh() : 0,
                            'low' => $candle->getLow() ? $candle->getLow() : 0,
                            'volume' => $candle->getVolume() ? $candle->getVolume() : 0,
                            'time' => $candle->getTime() ? $candle->getTime() : 0,
                            'interval' => $candle->getInterval() ? $candle->getInterval() : 0,
                        ]
                    );
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        return response([
            'status' => true,
        ], 200);
    }
    public function chartStrategy(Request $request)
    {
        $id = $request->route('id');
        $strategy = TestStrategy::find($id);

        $order_models = Order::where('strategy_id', $strategy->id)->get();
        $orders = [];
        foreach ($order_models as $item)
        {
            $order_time = Carbon::parse($item->created_at);
            $order_indicators_time = str_pad($order_time->timestamp, 13, "0");
            //$orders [] = [$order_indicators_time, "Bay. Price: " . $order->current_price, 1, "#34a853", 0.55];
            $orders [] = [$order_indicators_time, 1, $item->current_price,  "Bay. Price: " . $item->current_price];
        }
        
        //$order_time = Carbon::parse($order->created_at);
        $start_period = Carbon::parse($strategy->start_period)->format('Y-m-d H:i:00');
        $end_period = Carbon::parse($strategy->end_period);
        $wanted_end_period = Carbon::create($end_period->year, $end_period->month, $end_period->day, 23, $end_period->minute,0)->format('Y-m-d H:i:00');

        $stock_id = Stock::where('figi', $strategy->figi)->first()->id;

        $candles = Candle::where('tools_id', '=', $stock_id)->where('tools_type', '=', 'stock')->where('interval', '=', '5min')
            ->where('time', '>=', $start_period)
            ->where('time', '<=', $wanted_end_period)->orderBy('time', 'asc')->get();

        $list = [];
        $key_time = [];

        foreach ($candles as $item) {
            $timestamp = str_pad(Carbon::parse($item->time)->addHours(3)->timestamp, 13, "0");
            if (!array_key_exists($timestamp, $key_time)) {
                $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                $key_time[$timestamp] = $timestamp;
            }
        }

        return response([
            'candles' => $list,
            'order' => $orders,
            'list_take_profit1' => [],
            'list_take_profit2' => [],
            'list_stop_orders1' => [],
            'list_stop_orders2' => [],
        ], 200);
    }

    public function setOrdersTest(Request $request)
    {
        $rows = $request->post('selRows');
        $time_start = strtotime($rows['row']['start_period']);
        $time_end = strtotime($rows['row']['end_period']);
        $start_period = date('Y-m-d', $time_start);
        $end_period = date('Y-m-d', $time_end);;
        $time_frame = $rows['row']['time_frame'];
        $figi = $rows['row']['figi'];
        $strategy_id = $rows['row']['id'];
        $string_command = "-sp $start_period -ep $end_period -tf $time_frame -f $figi -si $strategy_id";
        
        $command = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = escapeshellcmd("C:/wamp64/www/trader/venv/Scripts/python C:/wamp64/www/trader/test_strategy_from_laravel.py $string_command");
        } else {
            $command = escapeshellcmd("/var/www/trader/env/bin/python /var/www/trader/test_strategy_from_laravel.py $string_command");
        }

        $output = shell_exec($command);

        return response([
            'status' => true,
        ], 200);
    }

    public function deleteOrdersTest(Request $request)
    {
        $rows = $request->post('selRows');

        $strategy = TestStrategy::find($rows['row']['id']);
        $strategy->update(['is_completed' => 0]);
        $order_models = Order::where('strategy_id', $strategy->id)->delete();

        return response([
            'status' => true,
        ], 200);
    }

    public function openOrders(Request $request)
    {
        $id = $request->route('id');
        $objects = Order::where('strategy_id', '=', $id)->orderByDesc('created_at');
        return response()->json([
            'orders'  => $objects->get()->toArray(),
        ]);
    }
}
