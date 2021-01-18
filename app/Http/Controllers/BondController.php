<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Bond;

class BondController extends Controller
{
    public function all(Request $request)
    {
        $bonds = Bond::all();
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
    
        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');
        if ($request->has('q')) $q = $request->query('q');
          
        $bonds = Bond::search($q)->orderBy('updated_at', $orderBy)->paginate($perPage);

        return view('bond.all', compact('bonds', 'orderBy', 'sortBy', 'q', 'perPage'));
    }
    public function newBond(Request $request)
    {
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
    
        if ($request->has('orderBy')) $orderBy = $request->query('orderBy');
        if ($request->has('sortBy')) $sortBy = $request->query('sortBy');
        if ($request->has('perPage')) $perPage = $request->query('perPage');
        if ($request->has('q')) $q = $request->query('q');
          
        $bonds = Bond::search($q)->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->orderBy('updated_at', $orderBy)->paginate($perPage);

        return view('bond.new', compact('bonds', 'orderBy', 'sortBy', 'q', 'perPage'));
    }

    public function favorites(Request $request)
    {
        $perPage = 100;
        $bonds = Auth::user()->favoritesBond;
        return view('bond.favorites', compact('bonds', 'perPage'));
    }
    public function favoriteBond(Request $request)
    {
        if ($request->method() == 'POST') {
            $select = $request->get('selection');

            //Студент должен иметь заполненную анкету, иначе не установить родителя 
            Auth::user()->favoritesBond()->attach(array_values($select));
            return redirect(route('bond.favorites'));
        }
    }
    public function unFavoriteBond(Request $request)
    {
        if ($request->method() == 'POST') {
            $select = $request->get('selection');

            //Студент должен иметь заполненную анкету, иначе не установить родителя 
            Auth::user()->favoritesBond()->detach(array_values($select));
            return redirect(route('bond.favorites'));
        } 
    }

    public function trash(Request $request)
    {

    }
}
