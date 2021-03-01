<?php

namespace App\Http\Controllers;

use \jamesRUS52\TinkoffInvest\TIClient;
use \jamesRUS52\TinkoffInvest\TISiteEnum;
use \jamesRUS52\TinkoffInvest\TICurrencyEnum;
use \jamesRUS52\TinkoffInvest\TIInstrument;
use \jamesRUS52\TinkoffInvest\TIPortfolio;
use \jamesRUS52\TinkoffInvest\TIOperationEnum;
use \jamesRUS52\TinkoffInvest\TIIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandleIntervalEnum;
use \jamesRUS52\TinkoffInvest\TICandle;
use \jamesRUS52\TinkoffInvest\TIOrderBook;
use \jamesRUS52\TinkoffInvest\TIInstrumentInfo;

use Carbon\Carbon;
use App\Operation;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $time = Carbon::now();
        
        $client = new TIClient(env('TOKEN_TINKOFF') ,TISiteEnum::EXCHANGE);
        $accounts = $client->getAccounts(); 
        $port = $client->getPortfolio($accounts);

        $operations = Operation::where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay())->orderBy('date', 'asc')->get();

        return view('dashboard', compact('port', 'operations'))->with('time', $time->toDateTimeString());
    }
}
