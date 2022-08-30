<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Stock;

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

class Stocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:stock';

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
        $client = new TIClient(env('TOKEN_TINKOFF') ,TISiteEnum::EXCHANGE);
        $bonds = $client->getStocks();
        foreach ($bonds as $item)
        {
            $figi = $item->getFigi();
            $ticker = $item->getTicker();
            $isin = $item->getIsin();
            $minPriceIncrement = $item->getMinPriceIncrement() ? $item->getMinPriceIncrement() : 0;
            $currency = $item->getCurrency();
            $name = $item->getName();

            $model = Stock::firstOrCreate(['figi' => $figi, 'name' => $name],                        
                [
                                'figi' => $figi, 
                                'ticker' => $ticker, 
                                'isin' => $isin, 
                                'minPriceIncrement' => $minPriceIncrement,
                                'currency' => $currency,
                                'name' => $name,
                                'is_dividend' => 0,
            ]);
            if ($model->wasRecentlyCreated) {
                // model just created in the database; it didn't exist before.
                echo "$model->name добавлена\n";
            } else {
                // model already existed and was pulled from database.
            }
        }

        return 0;
    }
}
