<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\User;
use App\Stock;
use App\Candle;
use App\EmaDayIndicator;
use App\Profit;

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


class StockController extends Controller
{
    public function all(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();

        $models = Stock::whereNotIn('id', $favorite_ids)->get();

        return view('stock.all', [
            'stocks' => $models
        ]);
    }

    public function stockRub(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        $models = Stock::where('currency', '=', 'RUB')->whereNotIn('id', $favorite_ids)->get();

        return view('stock.rub', [
            'stocks' => $models
        ]);
    }

    public function dividends(Request $request)
    {
        $models = Stock::where('is_dividend', '=', true)->get();

        return view('stock.dividends', [
            'stocks' => $models
        ]);
    }

    public function setDividends(Request $request)
    {
        $rows = $request->post('selRows');
        foreach ($rows as $value) {
            Stock::where('id', $value)->update(['is_dividend' => 1]);
        }
  
        return response()->json([
            'cod' => 200
        ], 200);
    }

    public function stockUsd(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();

        $models = Stock::where('currency', '=', 'USD')->whereNotIn('id', $favorite_ids)->get();

        return view('stock.usd', [
            'stocks' => $models
        ]);
    }

    public function newStock(Request $request)
    {
        $models = Stock::where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->get();
        return view('stock.new', [
            'stocks' => $models
        ]);
    }

    public function favorite(Request $request)
    {
        $models = Auth::user()->favoritesStock;
        //$testModel = $models->first();
        //$testavg = $testModel->testavg;
        //$testRsi = $testModel->rsi;
        return view('stock.favorite', [
            'stocks' => $models
        ]);
    }
    public function test(Request $reqeust)
    {
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $favorite_ids = $user->favoritesStock->pluck('id')->toArray();

        $stocks = Stock::whereIn('id', $favorite_ids)->orderBy('updated_at')->get();
        $messageText = '';
        $list_img = [];
        foreach ($stocks as $item) {
            //Находим старый индикатор,если нет, то создаем. 
            $old_indicator = EmaDayIndicator::where('stock_id', $item->id)
                ->orderByDesc('created_at')->first();
 
            if ($old_indicator == null) {
                $new_indicator = EmaDayIndicator::create([
                    'stock_id' => $item->id,
                    'action' => $item->Average15day,
                    'send_telegramm' => false,
                ]);
            } else {
                //Если есть старый индикатор, сравниваем со старым значением,
                //если разные вносим создаем новый и отправляем в телеграмм.
                if ($old_indicator->action != $item->Average15day || $old_indicator->send_telegramm == false) {
                    echo $old_indicator->action . " new " . $item->Average15day . $old_indicator->id . "\n";
                    //Отпраялем в телеграмм событие
                    if ($item->Average15day != "nothing") {
                        $stop_los = $item->min_precent;
                        $take_profit = $item->take_profit;

                        $new_indicator = EmaDayIndicator::create([
                            'stock_id' => $item->id,
                            'action' => $item->Average15day,
                            'send_telegramm' => true,
                        ]);
                        echo 'break';
                    }
                }
            }
        }
    }

    public function emachart(Request $request)
    {
        $id = $request->route('id');
        if ($request->ajax()) {
            $candle = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')->pluck('close', 'time')->toArray();
            return response()->json($candle);
        } else {
            $models = EmaDayIndicator::where('stock_id', $id)
                ->orderByDesc('created_at')->limit(100)->get();
            $ema_indicators = [];
            $indicators = $models->where('action', 'buy')->pluck('updated_at')->toArray();
            $count = 0;
            foreach ($indicators as $item_carbon) {
                $ema_indicators_time = str_pad($item_carbon->addHours(3)->timestamp, 13, "0");
                if ($count == 1) {
                    $ema_indicators[] = [$ema_indicators_time, "Bay Ema", 1, "#34a853", 0.55];
                    $count = 0;
                }
                //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
                $ema_indicators[] = [$ema_indicators_time, "Bay Ema", 0, "#34a853", 0.75];
                $count++;
            }



            $sell_indicators = $models->where('action', 'sell')->pluck('updated_at')->toArray();
            $count_sell = 0;
            foreach ($sell_indicators as $item_carbon) {
                $ema_indicators_time = str_pad($item_carbon->addHours(3)->timestamp, 13, "0");
                if ($count_sell == 1) {
                    $ema_indicators[] = [$ema_indicators_time, "Sell Ema", 1, "#a84734", 0.55];
                    $count_sell = 0;
                }
                //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
                $ema_indicators[] = [$ema_indicators_time, "Sell Ema", 0, "#a84734", 0.75];
                $count++;
            }



            $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')
                ->where('created_at', '>=', Carbon::now()->subDays(20)->startOfDay())->orderBy('time', 'asc')->get();
            $list = [];
            $rsi_data = [];
            $rsi_raw = [];
            $key_time = [];
            $key_time_rsi = [];
            foreach ($candles as $item) {
                $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
                if (!array_key_exists($timestamp, $key_time)) {
                    $rsi_raw['close'][] = $item->close;
                    $rsi_raw['time'][] = $timestamp;
                    $key_time_rsi[$timestamp] = $timestamp;
                }
            }
            if (array_key_exists('close', $rsi_raw)) {
                $rsi = trader_rsi($rsi_raw['close'], 14);
                foreach ($rsi as $key => $value) {
                    $time = $rsi_raw['time'][$key];
                    $rsi_data[] = [$time, $value];
                }
            }

            foreach ($candles as $item) {
                $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
                if (!array_key_exists($timestamp, $key_time)) {
                    $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                    $key_time[$timestamp] = $timestamp;
                }
            }
            return view('stock.emachart', [
                'event' => $models,
                'candles' => $list,
                'ema_indicators' => $ema_indicators,
                'rsi_data' => $rsi_data,
            ]);
        }
    }

    public function emachartToday(Request $request)
    {
        $id = $request->route('id');
        if ($request->ajax()) {
            $candle = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')->pluck('close', 'time')->toArray();
            return response()->json($candle);
        } else {
            $models = EmaDayIndicator::where('stock_id', $id)->where('created_at', '>=', Carbon::now()->subHours(24)->startOfDay())
                ->orderByDesc('created_at')->limit(100)->get();
            $ema_indicators = [];
            $indicators = $models->where('action', 'buy')->pluck('updated_at')->toArray();
            $count = 0;
            foreach ($indicators as $item_carbon) {
                $ema_indicators_time = str_pad($item_carbon->addHours(3)->timestamp, 13, "0");
                if ($count == 1) {
                    $ema_indicators[] = [$ema_indicators_time, "Bay Ema Indicator", 1, "#34a853", 0.55];
                    $count = 0;
                }
                //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
                $ema_indicators[] = [$ema_indicators_time, "Bay Ema Indicator", 0, "#34a853", 0.75];
                $count++;
            }
            $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')
                ->where('time', '>=', Carbon::now()->subHours(24)->startOfDay())->orderBy('time', 'asc')->get();
            $list = [];
            $rsi_data = [];
            $rsi_raw = [];
            $key_time = [];
            $key_time_rsi = [];
            foreach ($candles as $item) {
                $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
                if (!array_key_exists($timestamp, $key_time)) {
                    $rsi_raw['close'][] = $item->close;
                    $rsi_raw['time'][] = $timestamp;
                    $key_time_rsi[$timestamp] = $timestamp;
                }
            }
            if (array_key_exists('close', $rsi_raw)) {
                $rsi = trader_rsi($rsi_raw['close'], 20);
                if ($rsi != false) {
                    foreach ($rsi as $key => $value) {
                        $time = $rsi_raw['time'][$key];
                        $rsi_data[] = [$time, $value];
                    }
                }
            }

            foreach ($candles as $item) {
                $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
                if (!array_key_exists($timestamp, $key_time)) {
                    $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                    $key_time[$timestamp] = $timestamp;
                }
            }
            return view('stock.emachart-today', [
                'event' => $models,
                'candles' => $list,
                'ema_indicators' => $ema_indicators,
                'rsi_data' => $rsi_data,
            ]);
        }
    }
    public function action(Request $request)
    {
        $id = $request->route('id');
        $model = Stock::find($id);
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);

        $accounts = $client->getAccounts();
        $port = $client->getPortfolio($accounts);
        $info = $client->getInstrumentInfo($model->figi);
        $balance = 0;
        foreach ($port->getAllCurrencies() as $item) {
            if ($item->getCurrency() == 'RUB') {
                $balance = $item->getBalance();
                break;
            }
        }
        $lot = $info->getLot();
        $price = $model->last_price;

        $max_lots = floor(($balance / $price) / $lot);
        //$instr = $client->getOrderBook($model->figi, 1);

        //block for debug

        //end block

        return view('stock.action', ['model' => $model, 'id' => $model->id, 'price' => $price, 'max_lots' => $max_lots]);
    }

    public function order(Request $request)
    {
        $id = $request->post('id');
        $max_lots = $request->post('max_lots');
        $price = $request->post('price');
        $max_lots = $max_lots;
        $model = Stock::find($id);
        $take_profit = $model->last_price + ($model->last_price * 0.0015);
        $stop_loss = $model->last_price - ($model->last_price * 0.0019);

        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);

        $order_market = $client->sendOrder($model->figi, (int)$max_lots, TIOperationEnum::BUY);

        $profit = Profit::create([
            'order_id' => $order_market->getOrderId(),
            'figi' => $model->figi,
            'price' => $price,
            'take_profit' => $take_profit,
            'stop_loss' => $stop_loss,
        ]);

        //$order_take = $client->sendOrder($model->figi, 1, TIOperationEnum::SELL);

        return redirect()->route('home');
    }

    public function favoriteStock(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->favoritesStock()->attach(array_values($select));

        return response()->json([
            'cod' => 200
        ], 200);
    }

    public function unFavoriteStock(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->favoritesStock()->detach(array_values($select));

        return response()->json([
            'cod' => 200
        ], 200);
    }
    
    public function profit(Request $request)
    {
        $profits = Profit::all();
        return view('stock.profit', ['profits' => $profits]);
    }
    
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $id = $request->id;
            $model = Profit::find($id);
            $model->take_profit = $request->takeProfit;
            $model->stop_loss = $request->stopLoss;
            $model->save();
            return redirect()->route('stock.profit.update', ['id' => $id]);
        }
        if ($request->isMethod('get')) {
            $id = $request->id;
            $profit = Profit::find($id);
            $take_profit = $profit->take_profit;
            $stop_loss = $profit->stop_loss;
            return view('stock.profit.update', ['take_profit' => $take_profit, 
                                                'stop_loss' => $stop_loss,
                                                'id' => $id]);
        }    
    }
}
