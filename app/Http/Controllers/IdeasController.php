<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Idea;

class IdeasController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'models' => Idea::all()->toArray(),
            'status' => true,
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        return response([
            'idea' => Idea::findOrFail($id),
        ], 200);
    }

    public function update(Request $request)
    {
        $idea = Idea::findOrFail($request->idea_id);
        $idea->update([
            'figi' => $request->figi_idea,
            'name' => $request->name_idea,
            'action' => $request->type_idea,
            'min_period' => $request->limit_day_idea,
            'aim_price' => $request->aim_idea,
            'description' => $request->description_idea,
            'status' => $request->status_idea,
        ]);
        
        return response([
            'status' => true,
        ], 200);
    }

    public function store(Request $request)
    {
        $model = Idea::create([
            'figi' => $request->figi_idea,
            'name' => $request->name_idea,
            'action' => $request->type_idea,
            'min_period' => $request->limit_day_idea,
            'aim_price' => $request->aim_idea,
            'description' => $request->description_idea,
            'status' => $request->status_idea,
        ]);
        return response([
            'status' => true,
        ], 200);
    }
}
