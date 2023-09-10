<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Etf;
use App\User;
use App\Candle;

class EtfController extends Controller
{
    public function all(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesEtf->pluck('id')->toArray();
        $objects = Etf::whereNotIn('id', $favorite_ids);
        $count = $objects->count();
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $name = $request->get('name');
        $created_by = $request->get('created_by');
        $type = $request->get('type');
        $limit = 20;
        $page = (int) $request->get('page');
        $created_at = $request->get('created_at');

        if ($name !== null) {
            $objects->where('name', 'like', '%' . $name['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'etfs' => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function favorite(Request $request)
    {
        $models = Auth::user()->favoritesEtf;
        return response([
            'etfs' => $models,
        ], 200);
    }

    public function favoriteEtf(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        Auth::user()->favoritesEtf()->attach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function unFavoriteEtf(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        Auth::user()->favoritesEtf()->detach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function miniCandleCharts(Request $request)
    {
        $id = $request->route('id');

        $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'etf')->where('interval', '=', 'day')
            ->where('created_at', '>=', Carbon::now()->subMonths(7)->startOfDay())->orderBy('time', 'asc')->get();

        $list_data = [];
        $list_time = [];
        foreach ($candles as $item) {
            $list_data [] = $item->close;
            $list_time [] = $item->time;
        }
        return response([
            'candles' => $list_data,
            'time' => $list_time,
        ], 200);
    }
    public function chart1h(Request $request)
    {
        $id = $request->route('id');
        if ($request->ajax()) {
            $candle = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')->pluck('close', 'time')->toArray();
            return response()->json($candle);
        } else {
            $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'etf')->where('interval', '=', 'hour')
                ->where('created_at', '>=', Carbon::now()->subDays(20)->startOfDay())->orderBy('time', 'asc')->get();

            $mod = Etf::find($id);
            $test = $mod->ema_hour;
            $test2 = $mod->cci_hour;
            $test3 =  $mod->rsi_hour;
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

            return view('etf.chart-1h', [
                'candles' => $list,
                'rsi_data' => $rsi_data,
            ]);
        }
    }
    public function chart1d(Request $request)
    {
        $id = $request->route('id');
        if ($request->ajax()) {
            $candle = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'stock')->pluck('close', 'time')->toArray();
            return response()->json($candle);
        } else {
            $candles = Candle::where('tools_id', '=', $id)->where('tools_type', '=', 'etf')->where('interval', '=', 'day')
                ->where('created_at', '>=', Carbon::now()->subMonths(7)->startOfDay())->orderBy('time', 'asc')->get();

            $mod = Etf::find($id);
            $test = $mod->ema_hour;
            $test2 = $mod->cci_hour;
            $test3 =  $mod->rsi_hour;
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

            return view('etf.chart-1d', [
                'candles' => $list,
                'rsi_data' => $rsi_data,
            ]);
        }
    }
    public function saveRusEtf(Request $request)
    {
        $data = json_decode($request->value, true);
        foreach ($data as $item) {
            //$test = $item["figi"];
            $model = Etf::firstOrCreate(
                ['figi' => $item["figi"], 'name' => $item["name"]],
                [
                    'figi' => $item["figi"],
                    'ticker' => $item["ticker"],
                    'isin' => $item["isin"],
                    'faceValue' => 0,
                    'minPriceIncrement' => $item["minPriceIncrement"],
                    'currency' => $item["currency"],
                    'name' => $item["name"],
                    'is_dividend' => 0,
                ]
            );
        }

        return response()->json([
            'status' => true,
        ], 200);
    }
    public function getFavoriteNotAuth(Request $request)
    {
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $models = $user->favoritesEtf;
        return response([
            'etfs' => $models,
        ], 200);
    }
}
