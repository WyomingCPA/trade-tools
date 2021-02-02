<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Bond;
use App\User;

class BondController extends Controller
{
    public function all(Request $request)
    {
        $trash_ids = Auth::user()->trashBond->pluck('id')->toArray();
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        $notIn = array_merge(array_values($trash_ids), array_values($favorite_ids));

        $models = Bond::whereNotIn('id', $notIn)->get();

        return view('bond.all', [
            'bonds' => $models
        ]);
    }

    public function newBond(Request $request)
    {
        $models = Bond::where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->get();
        return view('bond.new', [
            'bonds' => $models
        ]);
    }

    public function favorites(Request $request)
    {
        $models = Auth::user()->favoritesBond;
        return view('bond.favorites', [
            'bonds' => $models
        ]);
    }
    public function favoriteBond(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->favoritesBond()->attach(array_values($select));
        
        return response()->json([
            'cod' => 200
        ], 200);
    }

    public function trashBond(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->trashBond()->attach(array_values($select));
        return response()->json([
            'cod' => 200
        ], 200);
    }

    public function unFavoriteBond(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->favoritesBond()->detach(array_values($select));

        return response()->json([
            'cod' => 200
        ], 200);
    }

    public function trash(Request $request)
    {
        $bonds = Auth::user()->trashBond;
        return view('bond.trash', compact('bonds'));
    }

    public function untrashBond(Request $request)
    {
        $rows = $request->post('selRows');
        $select = [];
        foreach ($rows as $value) {
            $select[] = $value['id'];
        }
        Auth::user()->trashBond()->detach(array_values($select));

       return response()->json([
            'cod' => 200
        ], 200);
    }

}
