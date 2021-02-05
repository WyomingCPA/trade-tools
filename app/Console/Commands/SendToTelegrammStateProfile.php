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

use TelegramBot\Api\BotApi;

class SendToTelegrammStateProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:telegrammstateprofile';

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
        $accounts = $client->getAccounts(); 
        $port = $client->getPortfolio($accounts); 
        $messageText = '';
        foreach ($port->getAllinstruments() as $item) 
        {
            $name = $item->getName();
            $exceptedYeldValue = $item->getExpectedYieldValue();
            $portfel_item = "$name : $exceptedYeldValue" . ";\n";
            $messageText = $messageText . $portfel_item;
        }

        $chatId = '-414528593';

        $bot = new BotApi(env('TELEGRAM_TOKEN'));
        $bot->sendMessage($chatId, $messageText, 'HTML');   
        return 0;
    }
}
