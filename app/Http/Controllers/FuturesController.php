<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\User;
use App\Futures;
use App\Candle;


class FuturesController extends Controller
{
    public function index(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        $objects = Futures::whereNotIn('id', $favorite_ids);
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
        return response()->json([
            'futures'  => $objects->get()->toArray(),
            'count' => $count
        ]);
    }

    public function getFavorite(Request $request)
    {
        $models = Auth::user()->favoritesFuture;
        return response([
            'futures' => $models,
        ], 200);
    }

    public function getFavoriteNotAuth(Request $request)
    {
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $models = $user->favoritesFuture;
        return response([
            'futures' => $models,
        ], 200);
    }

    public function setFavorite(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        Auth::user()->favoritesFuture()->attach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function unFavoriteFutures(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }

        Auth::user()->favoritesFuture()->detach(array_values($select));

        return response()->json([
            'status' => true,
        ], 200);
    }

    public function getAllFutures(Request $request)
    {
        $command = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = escapeshellcmd("C:/wamp64/www/trader/venv/Scripts/python C:/wamp64/www/trader/laravel/get_all_futures.py");
        } else {
            $command = escapeshellcmd("/var/www/trader/env/bin/python /var/www/trader/laravel/get_all_futures.py");
        }
        $output = shell_exec($command);
        return response([
            'status' => true,
        ], 200);
    }

    public function storeAllFutures(Request $request)
    {
        $model = Futures::create([
            'name' => $request->name,
            'figi' => $request->figi,
            'ticker' => $request->ticker,
            'class_code' => $request->class_code,
            'lot' => $request->lot,
            'currency' => $request->currency,
        ]);
        return response([
            'status' => true,
        ], 200);
    }      
}
