<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Profit;
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

class CheckProfit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-profit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release StopLoss and TakeProfit on Server';

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
        $profit = Profit::all();
        foreach ($profit as $item)
        {
            $stock = Stock::where('figi', '=', $item->figi)->firstOrFail();

            $client = new TIClient(env('TOKEN_TINKOFF') ,TISiteEnum::EXCHANGE);
            $accounts = $client->getAccounts(); 
            $port = $client->getPortfolio($accounts);
            $lots = 0;
            foreach ($port->getAllinstruments() as $item_port) {
                if ($item_port->getFigi() == $item->figi) {
                    $lots = $item_port->getLots();
                    break;
                }
            }
            if ($stock->last_price >= $item->stop_loss || $stock->last_price <= $item->take_profit)
            {
                //Продаем инструмент если срабатывает одно из условий.
                if ($lots != 0)
                {
                    $order_take = $client->sendOrder($item->figi, (int)$lots, TIOperationEnum::SELL);
                    $item->delete();
                    echo "Sell \n";
                }
                else {
                    $item->delete();
                }
            }
            else
            {
                echo $lots;
                echo "Ничего \n";
            }
            
        }
        
    }
}
