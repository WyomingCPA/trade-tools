<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

use App\Bond;
use App\Candle;

class GetCandleBond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getcandlebond';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = 50;
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $bonds = Bond::where('faceValue', '!=', 0)->orderBy('updated_at')->take($limit)->get();
        $i = 0;
        foreach ($bonds as $bond) 
        {
            //Перед записью удаляем все старые свечи.
            $deleteCandleRows = Candle::where('tools_id', '=', $bond->id)->delete();

            $from = new \DateTime();
            $from->sub(new \DateInterval("P7D"));
            $to = new \DateTime();
            $candles = $client->getHistoryCandles($bond->figi, $from, $to, TIIntervalEnum::HOUR);
            foreach ($candles as $candle)
            {
                $model = Candle::firstOrCreate(['tools_id' => $bond->id], 
                [                
                    'tools_id' => $bond->id,
                    'open' => $candle->getOpen() ? $candle->getOpen() : 0,
                    'close' => $candle->getClose() ? $candle->getClose() : 0,
                    'high' => $candle->getHigh() ? $candle->getHigh() : 0,
                    'low' => $candle->getLow() ? $candle->getLow() : 0,
                    'volume' => $candle->getVolume() ? $candle->getVolume() : 0,
                    'time' => $candle->getTime() ? $candle->getTime() : 0, 
                    'interval' => $candle->getInterval() ? $candle->getInterval() : 0,                  
                ]);
            }

            echo $bond->figi . "\n";
            $bond->touch();
            $i++;
        }


        //$from = new \DateTime();
        //$from->sub(new \DateInterval("P7D"));
        //$to = new \DateTime();

        //$candles = $client->getHistoryCandles("BBG00T22WKV5", $from, $to, TIIntervalEnum::HOUR);
        //foreach ($candles as $item)
        //{

        //}
        return 0;
    }
}
