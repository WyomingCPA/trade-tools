<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;


use App\User;
use App\Candle;
use App\Cryptocurrency;
use App\Pools;

class CheckPoolsTelegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-pools-telegramm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pools positon';

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
        $favorite_ids = $user->favoritesCryptocurrency->pluck('id')->toArray();
        $messageText = "<b>Диапазоны пулов ликвидности</b>\n\n";
        foreach ($favorite_ids as $item) {
            $row = '';
            //Получаем последнюю свечу
            $last_candle = Candle::where('tools_id', '=', $item)->where('tools_type', '=', 'coins')
                ->where('interval', '=', '1h')->where('created_at', '>=', Carbon::now()->subMonths(1)->startOfDay())->orderBy('time', 'desc')->first();
            //echo "$last_candle->time $last_candle->tools_id $last_candle->close \n";
            //Получаем диапазон
            $model = Cryptocurrency::find($item);
            $pool = Pools::where('cryptocurrencies_id', $model->id)->first();

            $pool_min = 0;
            $pool_max = 0;

            if (!is_null($pool)) {
                $pool_min = $pool->min;
                $pool_max = $pool->max;
                if (($pool_min <= $last_candle->close) && ($last_candle->close <= $pool_max)) {
                    //echo "Цена в диапазоне";
                    $row = "$model->symbol: Range: $pool_min - $pool_max, Цена $last_candle->close в диапазоне\n\n";
                } 
                else {
                    //echo "Цена вне диапазона";
                    $row = "$model->symbol: Range: $pool_min - $pool_max, Цена $last_candle->close вне диапазона\n\n";
                }
            } else {
                //echo "Диапазон не задан";
                $row = "$model->symbol, Цена $last_candle->close Диапазон не задан\n\n";
            }
            $messageText .= $row;
        }

        $chatId = '-414528593';
        $bot = new BotApi(env('TELEGRAM_TOKEN'));
        $bot->sendMessage($chatId, $messageText, 'HTML');
        echo $messageText;

        return 0;
    }
}
