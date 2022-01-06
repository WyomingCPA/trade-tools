<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Bond;
use App\User;
//test
class BondController extends Controller
{
    public function all(Request $request)
    {
        $trash_ids = Auth::user()->trashBond->pluck('id')->toArray();
        $favorite_ids = Auth::user()->favoritesBond->pluck('id')->toArray();
        $notIn = array_merge(array_values($trash_ids), array_values($favorite_ids));

        $objects = Bond::whereNotIn('id', $notIn);

        $count = $objects->count();
        $sort       = $request->get('sort');
        $direction  = $request->get('direction');
        $name       = $request->get('name');
        $created_by = $request->get('created_by');
        $type       = $request->get('type');
        //$limit      = (int)$request->get('limit');
        $limit      = 20;
        $page       = (int)$request->get('page');
        $created_at = $request->get('created_at');

        if ($name !== null) {
            $objects->where('name', 'like', '%' . $name['searchTerm'] . '%');
        }
        $objects->offset($limit * ($page - 1))->limit($limit);

        if ($request->isMethod('post')) {
            return response()->json([
                'stocks'  => $objects->get()->toArray(),
                'count' => $count
            ]);
        }
    }

    public function newBond(Request $request)
    {
        $models = Bond::where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay())->get();
        return response([
            'bonds' => $models,
        ], 200);
    }

    public function favorites(Request $request)
    {
        $models = Auth::user()->favoritesBond;
        return response([
            'bonds' => $models,
        ], 200);
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
            'status' => true,
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
            'status' => true,
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
            'status' => true,
        ], 200);
    }

    public function trash(Request $request)
    {
        $bonds = Auth::user()->trashBond;
        return response([
            'bonds' => $bonds,
        ], 200);
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
            'status' => true,
        ], 200);
    }

}
