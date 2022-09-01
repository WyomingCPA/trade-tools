<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ConsoleLog;

class ConsoleLogController extends Controller
{
    public function store(Request $request)
    {
        $countIterration = ConsoleLog::count();
        if ($countIterration >= 2000)
        {
            $search = 'error';
            $models = ConsoleLog::where('message', 'not like', '%' . $search . '%')->delete();
        }
        $model = ConsoleLog::create([
            'type' => $request->type,
            'message' => $request->message,
            'data' => $request->value,
        ]);
        return response([
            'status' => true,
        ], 200);        
    }

    public function getLastEventsConsole(Request $request)
    {
        $models = ConsoleLog::latest()->take(20)->get();
        return response([
            'status' => true,
            'events' => $models,
        ], 200); 
    }

    public function deleteAll(Request $request)
    {
        $models = ConsoleLog::truncate();

        return response()->json([
            'status' => true,
        ], 200);
    }
}
