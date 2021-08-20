<?php

namespace App\Console\Commands;

use App\Portfel;
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

class GetPortfolio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get-portfolio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Portfolio';

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
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $accounts = $client->getAccounts();
        $port = $client->getPortfolio($accounts);
        //protected $fillable = ['tools_type', figi', 'ticker', 'isin', 'currency', 'name', 'lots', 'expectedYieldValue', 'averagePositionPrice'];
        foreach ($port->getAllinstruments() as $item) {
            if ($item->getInstrumentType() != 'currency') {
                $portfolio = new Portfel;
                $portfolio->tools_type = $item->getInstrumentType();
                $portfolio->figi = $item->getFigi();
                $portfolio->ticker = $item->getTicker();
                $portfolio->isin = $item->getIsin() ? $item->getIsin() : 0;
                $portfolio->currency = $item->getAveragePositionPrice()->currency;
                $portfolio->name = $item->getName();
                $portfolio->lots = $item->getLots();
                $portfolio->expectedYieldValue = $item->getExpectedYieldValue();
                $portfolio->averagePositionPrice = $item->getAveragePositionPrice()->value;

                $portfolio->save();
            }
        }
        return 0;
    }
}
