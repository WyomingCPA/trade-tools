<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Log;


class LogController extends Controller
{
    public function store(Request $request)
    {
        $test = json_encode($request->value);
        $model = Log::create([
            'type' => $request->type,
            'message' => $request->message,
            'data' => $request->value,
        ]);
        return response([
            'status' => true,
        ], 200);        
    }
    public function globalLog(Request $request)
    {
        $objects = Log::where('updated_at', '>=', Carbon::now()->subDays(7)->startOfDay())->orderBy('created_at', 'desc');

        $type = $objects->pluck('type')->unique()->toArray();
        $count = $objects->count();
        $sort = $request->get('sort');
        $direction = $request->get('direction');
        $name = $request->get('title');
        $type_value = $request->get('type_value');
        $created_by = $request->get('created_by');
        $limit = 20;
        $page = (int) $request->get('page');
        $created_at = $request->get('created_at');
        
        if ($name !== null) {
            $objects->where('message', 'like', '%' . $name['searchTerm'] . '%');
        }
        if ($type_value !==null)
        {
            $objects->where('type', $type_value);
        }
        $objects->offset($limit * ($page - 1))->limit($limit);
        if ($request->isMethod('post')) {
            return response()->json([
                'logs' => $objects->get()->toArray(),
                'count' => $count,
                'type' => $type
            ]);
        }
    }
}
