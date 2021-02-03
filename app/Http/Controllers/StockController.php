<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Stock;

class StockController extends Controller
{
    public function all(Request $request)
    {
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        $notIn = array_merge(array_values($favorite_ids), array_values($favorite_ids));

        $models = Stock::whereNotIn('id', $notIn)->get();

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
