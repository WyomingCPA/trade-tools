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
        foreach ($stocks as $item) {
            //Находим старый индикатор,если нет, то создаем. 
            $old_indicator = EmaDayIndicator::where('stock_id', $item->id)
                ->orderByDesc('created_at')->first();

            if ($old_indicator == null)
            {
                $new_indicator = EmaDayIndicator::create([
                    'stock_id' => $item->id,
                    'action' => $item->Average15day,
                    'send_telegramm' => false,
                ]);
            }
            else{
                //Если есть старый индикатор, сравниваем со старым значением,
                //если разные вносим создаем новый и отправляем в телеграмм.
                if ($old_indicator->action != $item->Average15day || $old_indicator->send_telegramm == false) {
                    echo $old_indicator->action . " new " . $item->Average15day . $old_indicator->id . "\n";
                    //Отпраялем в телеграмм событие
                    if ($item->Average15day != "nothing" || $item->adx != 'HOLD') {
                        $stop_los = $item->min_precent;
                        $take_profit = $item->take_profit;

                        $new_indicator = EmaDayIndicator::create([
                            'stock_id' => $item->id,
                            'action' => $item->Average15day,
                            'send_telegramm' => true,
                        ]);
    
                        $messageText .= "<a target='_blank' href='https://www.tinkoff.ru/invest/stocks/{$item->ticker}'>{$item->name} (EMA = {$new_indicator->action})(ADX = $item->adx) Price: {$item->last_price} Stop Loss={$stop_los}% Take Profit={$take_profit}%</a> \n";
                        $messageText .= "*******************************\n";
                    }
                }
            }
        }
        if (!empty($messageText)) {
            $chatId = '-597520329';
            $bot = new BotApi(env('TELEGRAM_TOKEN'));

            $bot->sendMessage($chatId, $messageText, 'HTML');
            //echo $messageText . "\n";
        }
    }
}
