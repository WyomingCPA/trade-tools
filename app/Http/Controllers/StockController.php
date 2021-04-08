<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        return view('stock.favorite', [
            'stocks' => $models
        ]);
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
            foreach ($indicators as $item_carbon)
            {
                $ema_indicators_time = str_pad($item_carbon->addHours(3)->timestamp, 13, "0");
                if ($count == 1)
                {
                    $ema_indicators [] = [$ema_indicators_time, "Bay Ema Indicator", 1, "#34a853", 0.55];
                    $count = 0;
                }
                //[1617198300000, "Bay Ema Indicator", 0, "#34a853", 0.75],
                $ema_indicators [] = [$ema_indicators_time, "Bay Ema Indicator", 0, "#34a853", 0.75];
                $count++;
            }
            $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')
                ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())->orderBy('time', 'asc')->get();
            $list = [];
            $key_time = [];
            foreach ($candles as $item) {
                $timestamp = str_pad(Carbon::parse($item->time)->addHours(6)->timestamp, 13, "0");
                if (!array_key_exists($timestamp, $key_time))
                {
                    $list[] = array((int)$timestamp, $item->open, $item->high, $item->low, $item->close, $item->volume);
                    $key_time [$timestamp] = $timestamp;
                }
            }

            return view('stock.emachart', ['event' => $models,
                                           'candles' => $list,
                                           'ema_indicators' => $ema_indicators, ]);
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
        
        $max_lots = floor(($balance / $price)/$lot);
        //$instr = $client->getOrderBook($model->figi, 1);

        //block for debug

        //end block

        return view('stock.action', ['model' => '$model', 'id' => $model->id, 'price' => $price, 'max_lots' => $max_lots]);
    }

    public function order(Request $request)
    {              
        $id = $request->post('id');
        $max_lots = $request->post('max_lots');
        $price = $request->post('price');
        $max_lots = $max_lots;
        $model = Stock::find($id);
        $take_profit = $model->last_price + ($model->last_price*0.0015);
        $stop_loss = $model->last_price - ($model->last_price*0.0019);

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
}
