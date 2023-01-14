<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


use App\Idea;

class CalculateController extends Controller
{
    public function getIdea(Request $request)
    {
        $id = $request->route('id');
        $model = Idea::find($id);

        return response()->json([
            'status' => true,
            'figi' => $model->figi ?? 'No',
        ], 200);
    }
    public function calculateLotsForStock(Request $request)
    {
        $figi = $request->figi;
        $summ = $request->summ;
        
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);

        $accounts = $client->getAccounts();
        $port = $client->getPortfolio($accounts);
        $info = $client->getInstrumentInfo($figi);

        $lot = $info->getLot();

        $from = new \DateTime();
        $from->sub(new \DateInterval("P7D"));
        $to = new \DateTime();
        try {
            $candles = $client->getHistoryCandles($figi, $from, $to, TIIntervalEnum::DAY);
        } catch (\Exception $e) {
            $max_lots = $e->getMessage();
        }
        $candle_item = array_values(array_slice($candles, -1))[0];
        $price = $candle_item->getClose();
        $max_lots = floor(($summ / $price) / $lot);

        return response()->json([
            'max_lots' => $max_lots,
            'status' => true,
        ], 200);
    }
}
