<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Candle;
use App\User;
use App\Stock;
use App\EmaDayIndicator;

use TelegramBot\Api\BotApi;


class CheckEmaIndicator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:checkemaindicator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check ema indicator, and send telegramm';

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
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $favorite_ids = $user->favoritesStock->pluck('id')->toArray();
        $stocks = Stock::whereIn('id', $favorite_ids)->orderBy('updated_at')->get();
        $messageText = '';
        foreach ($stocks as $item)
        {
            $indicator = EmaDayIndicator::firstOrCreate(['stock_id' => $item->id],
                                                        ['send_telegramm' => false,]);
            if ($indicator->action != $item->Average15day || $indicator->send_telegramm == false)
            {
                $indicator->action = $item->Average15day;
                $indicator->send_telegramm = true;
                $indicator->save();
                //Отпраялем в телеграмм событие
                if ($indicator->action != "nothing")
                {
                    $messageText .= "<a target='_blank' href='https://www.tinkoff.ru/invest/stocks/{$item->ticker}'>{$item->name} (EMA = {$indicator->action})(ADX = $item->adx) Price: {$item->last_price}</a> \n";
                    $messageText .= "*******************************\n";
                }                
            }
        }
        if (!empty($messageText))
        {
            $chatId = '-597520329';
            $bot = new BotApi(env('TELEGRAM_TOKEN'));

            $bot->sendMessage($chatId, $messageText, 'HTML');
        }
    }
}
