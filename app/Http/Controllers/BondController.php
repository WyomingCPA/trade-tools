<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

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
        //$bonds = Bond::where('created_at', '>=', Carbon::now()->subDays(1)->startOfDay())->get();
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
}
