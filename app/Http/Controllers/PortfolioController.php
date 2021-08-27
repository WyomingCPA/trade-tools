<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $tools = '';
        return view('portfolio.index', ['tools' => $tools]);
    }
}
