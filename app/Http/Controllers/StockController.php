<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\User;
use App\Stock;
use App\Candle;

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
        $objects = Stock::whereNotIn('id', $favorite_ids);
        $count = $objects->count();
        $sort       = $request->get('sort');
        $direction  = $request->get('direction');
        $name       = $request->get('name');
        $created_by = $request->get('created_by');
        $type       = $request->get('type');
        $limit      = 20;
        $page       = (int)$request->get('page');
        $created_at = $request->get('created_at');

        if ($name !== null) {
            $objects->where('name', 'like', '%' . $name['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function stockUsd(Request $request)
    {
        $objects    = Stock::where('currency', '=', 'USD');
        $count = $objects->count();
        $sort       = $request->get('sort');
        $direction  = $request->get('direction');
        $name       = $request->get('name');
        $created_by = $request->get('created_by');
        $type       = $request->get('type');
        //$limit      = (int)$request->get('limit');
        $limit      = 20;
        $page       = (int)$request->get('page');
        $created_at = $request->get('created_at');

        //$favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        //$models = Stock::where('currency', '=', 'RUB')->whereNotIn('id', $favorite_ids)->get();
        if ($name !== null) {
            $objects->where('name', 'like', '%' . $name['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);

        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function stockRub(Request $request)
    {
        $objects    = Stock::where('currency', '=', 'RUB');
        $count = $objects->count();
        $sort       = $request->get('sort');
        $direction  = $request->get('direction');
        $name       = $request->get('name');
        $created_by = $request->get('created_by');
        $type       = $request->get('type');
        //$limit      = (int)$request->get('limit');
        $limit      = 20;
        $page       = (int)$request->get('page');
        $created_at = $request->get('created_at');

        //$favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        //$models = Stock::where('currency', '=', 'RUB')->whereNotIn('id', $favorite_ids)->get();
        if ($name !== null) {
            $objects->where('name', 'like', '%' . $name['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);

        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function dividends(Request $request)
    {
        $models = Stock::where('is_dividend', '=', true)->get();
        return response([
            'stocks' => $models,
        ], 200);
    }

    public function setDividends(Request $request)
    {
        $rows = $request->post('selRows');
        foreach ($rows as $value) {
            Stock::where('id', $value)->update(['is_dividend' => true]);
        }
        return response()->json([
            'status' => true,
        ], 200);
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
        return response([
            'stocks' => $models,
        ], 200);
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

        /*
        $profit = Profit::create([
            'order_id' => $order_market->getOrderId(),
            'figi' => $model->figi,
            'price' => $price,
            'take_profit' => $take_profit,
            'stop_loss' => $stop_loss,
        ]);
        */
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
            'status' => true,
        ], 200);
    }



}
