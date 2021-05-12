<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Etf;
use App\User;
use App\Candle;
use App\AimEtf;

class EtfController extends Controller
{
    public function all(Request $request)
    {
        //$trash_ids = Auth::user()->trashEtf->pluck('id')->toArray();
        $favorite_ids = Auth::user()->favoritesEtf->pluck('id')->toArray();
        //$notIn = array_merge(array_values($trash_ids), array_values($favorite_ids));

        $models = Etf::whereNotIn('id', $favorite_ids)->get();

        return view('etf.all', [
            'etfs' => $models
        ]);
    }
    public function aim(Request $request)
    {
        $models = AimEtf::all();
        return view('etf.aim', [
            'aims' => $models
        ]);
    }
    public function aimCreate(Request $request)
    {
        if ($request->method() == 'GET') {
            $favorite_etfs = Auth::user()->favoritesEtf;
            return view('etf.aim.create', ['etfs' => $favorite_etfs]);
        }
        if ($request->method() == 'POST') {
            $rows = $request->post('selection');
            $name_target = $request->post('name_target');
            $descritpion = $request->post('descritpion');

            $aim = AimEtf::create([
                'aim_name' => $name_target,
                'event_detail' => $descritpion
            ]);
            
            $aim->etfs()->attach($rows);

            return redirect()->route('etf.aim');
        }
    }

    public function favorite(Request $request)
    {
        $models = Auth::user()->favoritesEtf;
        //$testModel = $models->first();
        //$testavg = $testModel->testavg;
        //$testRsi = $testModel->rsi;
        return view('etf.favorite', [
            'etfs' => $models
        ]);
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
            'cod' => 200
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
            'cod' => 200
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
                if ($rsi != false)
                {
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
                if ($rsi != false)
                {
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
}
