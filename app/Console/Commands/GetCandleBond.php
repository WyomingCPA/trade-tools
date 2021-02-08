<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

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
use App\User;

use Exception;
use Mockery\CountValidator\Exact;
use PhpParser\Node\Stmt\Catch_;

use TelegramBot\Api\BotApi;

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
        $limit = 4;
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $trash_ids = $user->trashBond->pluck('id')->toArray();
        $bonds = Bond::where('faceValue', '!=', 0)
                        ->whereNotIn('id', $trash_ids)
                        ->orderBy('updated_at')
                        ->take($limit)->get();
        $i = 0;
        $messageText = '';
        foreach ($bonds as $bond) 
        {
            //Перед записью удаляем все старые свечи.
            $last_price_bond = Candle::where('tools_id', '=', $bond->id)->where('tools_type','LIKE', '%bond%')->get()->last()->close ?? 0;
            $deleteCandleRows = Candle::where('tools_id', '=', $bond->id)->where('tools_type','LIKE', '%bond%')->delete();

            $from = new \DateTime();
            $from->sub(new \DateInterval("P7D"));
            $to = new \DateTime();
            try {
                $candles = $client->getHistoryCandles($bond->figi, $from, $to, TIIntervalEnum::HOUR);
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
                continue;
            }
            
            foreach ($candles as $candle)
            {
                try {
                    $model = Candle::firstOrCreate(['tools_id' => $bond->id], 
                    [                
                        'tools_id' => $bond->id,
                        'tools_type' => 'bond',
                        'open' => $candle->getOpen() ? $candle->getOpen() : 0,
                        'close' => $candle->getClose() ? $candle->getClose() : 0,
                        'high' => $candle->getHigh() ? $candle->getHigh() : 0,
                        'low' => $candle->getLow() ? $candle->getLow() : 0,
                        'volume' => $candle->getVolume() ? $candle->getVolume() : 0,
                        'time' => $candle->getTime() ? $candle->getTime() : 0, 
                        'interval' => $candle->getInterval() ? $candle->getInterval() : 0,                  
                    ]);
                }
                catch(\Illuminate\Database\QueryException $exception) 
                {
                    echo $exception->message + "\n";
                }
            }
            //Тут узнаем разницу в цене и отправляем в телегу, если цена изменилась сильно
            if (count($candles) != 0)
            {
                $lastCandle = $candles[count($candles)-1];
                $lastPrice = $lastCandle->getClose() ? $lastCandle->getClose() : 0;
                $decreaseValue = $last_price_bond - $lastPrice;

                $precent = ($decreaseValue / $last_price_bond) / 100;
                if ($precent < -3 || $precent > 3)
                {
                    $messageText .= "<a target='_blank' href='https://www.tinkoff.ru/invest/bonds/{$bond->ticker}'>{$bond->name} изменился на {$precent}%</a> \n";
                }
            }
            
            echo $bond->figi . "\n";
            $bond->touch();
            $i++;
        }

        if (!empty($messageText))
        {
            $chatId = '-597520329';
            $bot = new BotApi(env('TELEGRAM_TOKEN'));

            $bot->sendMessage($chatId, $messageText, 'HTML');
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
