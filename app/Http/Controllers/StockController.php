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
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
    
        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');
        if ($request->has('q')) $q = $request->query('q');
          
        $stocks = Stock::search($q)->orderBy('updated_at', $orderBy)->paginate($perPage);

        return view('stock.all', compact('stocks', 'orderBy', 'sortBy', 'q', 'perPage'));        
    }

    public function newStock(Request $request)
    {
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
    
        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');
        if ($request->has('q')) $q = $request->query('q');
          
        $stocks = Stock::search($q)->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->orderBy('updated_at', $orderBy)->paginate($perPage);

        return view('stock.new', compact('stocks', 'orderBy', 'sortBy', 'q', 'perPage'));
    }

    public function favorite(Request $request)
    {
        $perPage = 100;
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
