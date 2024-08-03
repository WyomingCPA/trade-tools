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
        if (!is_null($pool)) {
            $pool_min = $pool->min;
            $pool_max = $pool->max;
        }

        $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'coins')->where('interval', '=', '1d')
            ->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'asc')->get();

        $list_data = [];
        foreach ($candles as $item) {
            $dataPoints = [];
            $dataPoints['time'] = $item->time;
            $dataPoints['open'] = $item->open;
            $dataPoints['high'] = $item->high;
            $dataPoints['low'] = $item->low;
            $dataPoints['close'] = $item->close;
            $list_data[] = $dataPoints;
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
        if (!is_null($pool)) {
            $pool_min = $pool->min;
            $pool_max = $pool->max;
        }

        $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'coins')
            ->where('interval', '=', '1h')
            ->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())
            ->orderBy('time', 'asc')->get();

        $list_data = [];
        foreach ($candles as $item) {
            $dataPoints = [];
            $dataPoints['time'] = $item->time;
            $dataPoints['open'] = $item->open;
            $dataPoints['high'] = $item->high;
            $dataPoints['low'] = $item->low;
            $dataPoints['close'] = $item->close;
            $list_data[] = $dataPoints;
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
            foreach ($candle as $item) {
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
        $last_candle = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'coins')
            ->where('interval', '=', '1h')->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'desc')->first();
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
            'last_candle' => $last_candle->close,
        ], 200);
    }

    public function update(Request $request)
    {
        $product = Pools::where('cryptocurrencies_id', $request->id)->first();
        $product->update([
            'min' => $request->minRange,
            'max' => $request->maxRange,
            'balances' => $request->currentPrice,
        ]);

        return response([
            'status' => true,
        ], 200);
    }

    public function pools(Request $request)
    {
        $objects = Pools::where('created_at', '>=', Carbon::now()->subDay(20)->startOfDay())->orderBy('updated_at', 'desc');

        $count = $objects->count();
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $name = $request->get('title');
        $category_value = ['sexy'];
        $created_by = $request->get('created_by');
        $type = $request->get('type');
        $limit = 50;
        $page = (int) $request->get('page');
        $created_at = $request->get('created_at');

        return response([
            'pools' => $objects->get()->toArray(),
            'count' => $count,
            'status' => true,
        ], 200);
    }
    public function savePools(Request $request)
    {
        //$data = json_decode($request->post(), true);
        $data = $request->post();

        foreach ($data as $item) {
            //$test = $item["figi"];
            $model = Pools::create([
                'name' => $item["name"],
                'balances' => $item["assets_value"],
                'uncollected' => $item["comission"],
                'min' => 0,
                'max' => 0,
                'cryptocurrencies_id' => 0,
            ]);
        }
        return response([
            'status' => true,
        ], 200);
    }

    public function poolsData(Request $request)
    {
        $last_pools_30_min = [];
        $last_pools_30_min = Pools::where('created_at', '>', Carbon::now()->subMinutes(32)->toDateTimeString())->get()->unique('name')->toArray();
        $today_pools = Pools::whereDate('created_at',  Carbon::today());
        //$week_pools = Pools::where('created_at', '>', Carbon::today())->where('created_at', '<=', Carbon::today()->addDays(7));
        $week_pools = Pools::whereDate('created_at', '>', now()->subDays(7))->get();
        $month_pools = Pools::whereDate('created_at', '>', now()->subDays(31))->get();

        $today_pools_first_summ = null;
        $week_pools_first_summ = null;
        $today_pools_last_summ = null;
        $month_pools_first_summ = null;

        if ($today_pools->count() !==0)
        {
            $today_pools_first_time = $today_pools->first()->created_at;
            $today_pools_last_time = $today_pools->orderBy('created_at', 'DESC')->first()->created_at;
            $today_pools_first_summ = Pools::whereBetween('created_at', [Carbon::parse($today_pools_first_time)->subMinutes(2), Carbon::parse($today_pools_first_time)->addMinutes(2)])->get();
            $today_pools_last_summ = Pools::whereBetween('created_at', [Carbon::parse($today_pools_last_time)->subMinutes(2), Carbon::parse($today_pools_last_time)->addMinutes(2)])->get();
        }

        if ($week_pools->count() !==0)
        {
            $week_pools_first_time = $week_pools->first()->created_at;
            $week_pools_first_summ = Pools::whereBetween('created_at', [Carbon::parse($week_pools_first_time)->subMinutes(3), Carbon::parse($week_pools_first_time)->addMinutes(3)])->get();
        }
        if ($month_pools->count() !==0)
        {
            $month_pools_first_time = $month_pools->first()->created_at;
            $month_pools_first_summ = Pools::whereBetween('created_at', [Carbon::parse($month_pools_first_time)->subMinutes(3), Carbon::parse($month_pools_first_time)->addMinutes(3)])->get();
        }
       
        //Not working
        //$today_pools_last = $today_pools->latest->first();
        
        //$last_pools_30_min = array_unique($last_pools_30_min);
        return response([
            'last_pools_30_min' => $last_pools_30_min,
            'today_commission_summ' => $today_pools,
            'today_pools_first_summ' => $today_pools_first_summ?->sum('balances'),
            'today_pools_last_summ' => $today_pools_last_summ?->sum('balances'),
            'week_pools_first_summ' => $week_pools_first_summ?->sum('balances'),
            'month_pools_first_summ' => $month_pools_first_summ?->sum('balances'),
            'status' => true,
        ], 200);
    }
}
