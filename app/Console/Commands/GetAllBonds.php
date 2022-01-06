<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bond;

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


class GetAllBonds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getallbond';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда получает все облигаций';

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
        $client = new TIClient(env('TOKEN_TINKOFF') ,TISiteEnum::EXCHANGE);
        $bonds = $client->getBonds();
        foreach ($bonds as $item)
        {
            $figi = $item->getFigi();
            $ticker = $item->getTicker();
            $isin = $item->getIsin();
            //$faceValue = $item->getFaceValue() ? $item->getFaceValue() : 0;
            $faceValue = 0;
            $minPriceIncrement = $item->getMinPriceIncrement() ? $item->getMinPriceIncrement() : 0;
            $currency = $item->getCurrency();
            $name = $item->getName();

            $bond =Bond::firstOrCreate(['isin' => $isin],                        
                [
                                'figi' => $figi, 
                                'ticker' => $ticker, 
                                'isin' => $isin, 
                                'faceValue' => $faceValue, 
                                'minPriceIncrement' => $minPriceIncrement,
                                'currency' => $currency,
                                'name' => $name,
            ]);
        }

        return 0;
    }
}
