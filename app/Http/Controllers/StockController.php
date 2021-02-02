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
        $stocks = Auth::user()->favoritesStock;
        return view('stock.favorite', compact('stocks', 'perPage'));
    }

    public function favoriteStock(Request $request)
    {
        if ($request->method() == 'POST') {
            $select = $request->get('selection');

            $isTrash = $request->request->get('trash');
            $isFavorite = $request->request->get('favorites');

            if (isset($isFavorite))
            {
                Auth::user()->favoritesStock()->attach(array_values($select));
                return redirect(route('stock.favorites'));
            }

        }
    }

    public function unFavoriteStock(Request $request)
    {
        if ($request->method() == 'POST') {
            $select = $request->get('selection');
            Auth::user()->favoritesStock()->detach(array_values($select));
            return redirect(route('stock.favorites'));
        } 
    }
}
