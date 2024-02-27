<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


use App\Cryptocurrency;
use App\Candle;
use App\Pools;
use App\User;

class CryptocurrencyController extends Controller
{
    public function all(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesCryptocurrency->pluck('id')->toArray();
        $objects = Cryptocurrency::whereNotIn('id', $favorite_ids);
        $count = $objects->count();
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $name = $request->get('symbol');
        $created_by = $request->get('created_by');
        $type = $request->get('type');
        $limit = 20;
        $page = (int) $request->get('page');
        $created_at = $request->get('created_at');
        if ($name !== null) {
            $objects->where('symbol', 'like', '%' . $name . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'models' => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }
    
    public function chartDay(Request $request)
    {
        $pool_min = 0;
        $pool_max = 0;
        $id = $request->route('id');
        $model = Cryptocurrency::find($id);
        $pool = Pools::where('cryptocurrencies_id', $model->id)->first();
        if (!is_null($pool))
        {
            $pool_min = $pool->min;
            $pool_max = $pool->max;
        }

        $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'coins')->where('interval', '=', '1d')
            ->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'asc')->get();

        $list_data = [];
        foreach ($candles as $item) {
            $dataPoints = [];
            $dataPoints ['time'] = $item->time;
            $dataPoints ['open'] = $item->open;
            $dataPoints ['high'] = $item->high;
            $dataPoints ['low'] = $item->low;
            $dataPoints ['close'] = $item->close;
            $list_data [] = $dataPoints;
        }
        return response([
            'candles' => $list_data,
            'symbol' => $model->symbol,
            'pool_min' => $pool_min,
            'pool_max' => $pool_max,
        ], 200);
    }
    public function chartHour(Request $request)
    {
        $pool_min = 0;
        $pool_max = 0;
        $id = $request->route('id');
        $model = Cryptocurrency::find($id);
        $pool = Pools::where('cryptocurrencies_id', $model->id)->first();
        if (!is_null($pool))
        {
            $pool_min = $pool->min;
            $pool_max = $pool->max;
        }

        $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'coins')->where('interval', '=', '1h')
            ->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'asc')->get();

        $list_data = [];
        foreach ($candles as $item) {
            $dataPoints = [];
            $dataPoints ['time'] = $item->time;
            $dataPoints ['open'] = $item->open;
            $dataPoints ['high'] = $item->high;
            $dataPoints ['low'] = $item->low;
            $dataPoints ['close'] = $item->close;
            $list_data [] = $dataPoints;
        }
        return response([
            'candles' => $list_data,
            'symbol' => $model->symbol,
            'pool_min' => $pool_min,
            'pool_max' => $pool_max,
        ], 200);
    }
    public function favoriteCryptocurrency(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        Auth::user()->favoritesCryptocurrency()->attach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function favorite(Request $request)
    {
        $models = Auth::user()->favoritesCryptocurrency;
        return response([
            'models' => $models,
        ], 200);
    }

    public function unFavoriteCryptocurrency(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->favoritesCryptocurrency()->detach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function saveUsdtCryptocurrency(Request $request)
    {
        $data = json_decode($request->post()[0], true);
        foreach ($data as $item) {
            //$test = $item["figi"];
            $model = Cryptocurrency::firstOrCreate(
                ['symbol' => $item,],
                [
                    'symbol' => $item,
                ]
            );
            $model->save();
        }

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function saveCandle(Request $request)
    {
        $candles = json_decode($request->post()[0], true);

        foreach ($candles as $candle) {
            foreach ($candle as $item)
            {
                try {
                    $model = Candle::firstOrCreate(
                        ['tools_id' => $item['tools_id'], 'interval' => $item['interval'],  'tools_type' => $item['tools_type'], 'close' => $item['Close'], 'time' => $item['Time']],
                        [
                            'open' => $item['Open'],
                            'high' => $item['High'],
                            'low' => $item['Low'],
                            'volume' => $item['Volume'],                       
                        ]
                    );
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function getFavoriteNotAuth(Request $request)
    {
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $models = $user->favoritesCryptocurrency;
        return response([
            'coins' => $models,
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $model = Pools::firstOrCreate(
            ['cryptocurrencies_id' => $id,],
            [
                'balances' => 0,
                'min' => 0,
                'max' => 0,
            ]
        );
        return response([
            'model' => $model,
        ], 200);
    }

    public function update(Request $request)
    {
        $product = Pools::where('cryptocurrencies_id', $request->id)->first();
        $product->update([
            'min' => $request->minRange,
            'max' => $request->maxRange,
            'balances' => $request->balance,
        ]);

        return response([
            'status' => true,
        ], 200);
    }
}
