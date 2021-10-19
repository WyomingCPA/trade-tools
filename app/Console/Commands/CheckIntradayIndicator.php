<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Candle;
use App\User;
use App\Stock;
use App\EmaDayIndicator;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use HeadlessChromium\BrowserFactory;

class CheckIntradayIndicator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-intraday-indicator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check indicator in Intraday, and send telegramm';

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
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $chromeBinary = 'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe';
        } else {
            $chromeBinary = 'google-chrome';
        }

        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $favorite_ids = $user->favoritesStock->pluck('id')->toArray();

        $stocks = Stock::whereIn('id', $favorite_ids)->orderBy('updated_at')->get();
        $messageText = '';
        $list_img = [];
        foreach ($stocks as $item) {
            $ema = $item->Average15day;
            $rsi = $item->rsi;
            $cci = $item->cci;

            //if ($ema == 'nothing' || $rsi > 60 || $cci > -50) {
            //    continue;
            //}
            if ($cci >= -90) {
                continue;
            }
            //Находим старый индикатор,если нет, то создаем. 
            $old_indicator = EmaDayIndicator::where('stock_id', $item->id)
                ->orderByDesc('created_at')->first();

            if ($old_indicator == null) {
                $new_indicator = EmaDayIndicator::create([
                    'stock_id' => $item->id,
                    'action' => $ema,
                    'send_telegramm' => false,
                ]);
            } else {
                //Если есть старый индикатор, сравниваем со старым значением,
                //если разные вносим создаем новый и отправляем в телеграмм.
                if ($old_indicator->action != $ema || $old_indicator->send_telegramm == false) {

                    echo $old_indicator->action . " new " . $ema . $old_indicator->id . "\n";
                    //Отпраялем в телеграмм событие
                    $stop_los = $item->min_precent;
                    $take_profit = $item->take_profit;

                    $new_indicator = EmaDayIndicator::create([
                        'stock_id' => $item->id,
                        'action' => $ema,
                        'send_telegramm' => true,
                    ]);

                    //$browserFactory = new BrowserFactory('C:\Program Files (x86)\Google\Chrome\Application\chrome.exe');
                    $browserFactory = new BrowserFactory($chromeBinary);
                    // starts headless chrome
                    $browser = $browserFactory->createBrowser(['windowSize' => [1920, 1080],]);

                    try {
                        // creates a new page and navigate to an url
                        $page = $browser->createPage();
                        $page->navigate('http://trade-tools.ani24.fun/stock/emachart-today/' . $item->id)->waitForNavigation('networkIdle', 10000);

                        $path = public_path() . '/storage/';
                        $file = $item->ticker . '_' . time() . '.jpg';
                        $file_name = $path . $file;
                        $page->screenshot([
                            'format'  => 'jpeg',
                            'quality' => 100,
                        ])->saveToFile($file_name);
                        $list_img[] = $file;
                    } finally {
                        $browser->close();
                    }

                    $messageText .= " <a target='_blank' href='https://www.tinkoff.ru/invest/stocks/{$item->ticker}'>{$item->name}</a>\n";
                    $messageText .= " EMA = {$ema}\n RSI: {$rsi}\n Price: {$item->last_price} \n";
                    $messageText .= " <a target='_blank' href='http://trade-tools.ani24.fun/stock/action/{$item->id}'>Order</a>\n";
                    $messageText .= "*******************************\n";
                }
            }
        }
        if (!empty($messageText)) {
            $chatId = '-597520329';
            $bot = new BotApi(env('TELEGRAM_TOKEN'));

            $bot->sendMessage($chatId, $messageText, 'HTML');
            $media = new ArrayOfInputMedia();
            foreach ($list_img as $img) {
                $url = Storage::url($img);
                echo $img . "\n";
                echo $url . "\n";
                $media->addItem(new InputMediaPhoto('http://trade-tools.ani24.fun/storage/' . $img));
            }

            $bot->sendMediaGroup($chatId, $media);

            //echo $messageText . "\n";
        }
    }
}
