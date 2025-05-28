<?php

namespace App\Console\Commands\telegram;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Candle;
use App\User;
use App\Etf;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\InputMedia\InputMediaPhoto;
use \TelegramBot\Api\Types\InputMedia\ArrayOfInputMedia;
use HeadlessChromium\BrowserFactory;

class CheckCryptocurrencyIndicator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send-telegramm-cryptocurrency-indicator';

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
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $chromeBinary = 'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe';
        } else {
            $chromeBinary = 'google-chrome';
        }
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $favorite_ids = $user->favoritesEtf->pluck('id')->toArray();

        $messageText = '';
        $list_img = [];

        $browserFactory = new BrowserFactory($chromeBinary);
        // starts headless chrome
        $browser = $browserFactory->createBrowser(['windowSize' => [1920, 1080],]);
        try {
            // creates a new page and navigate to an url
            $page = $browser->createPage();
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $page->navigate('http://localhost:3000/public/table-indicator')->waitForNavigation('networkIdle', 10000);
            } else {
                $page->navigate('http://trade-tools.ani24.fun/public/table-indicator/')->waitForNavigation('networkIdle', 10000);
            }

            $path = public_path() . '/storage/';
            $file = time() . '.jpg';
            $file_name = $path . $file;
            $page->screenshot([
                'format'  => 'jpeg',
                'quality' => 100,
            ])->saveToFile($file_name);
            $list_img[] = $file;
            echo $file . "\n";
        } finally {
            $browser->close();
        }
        
        $messageText .= "test\n";
        $messageText .= "test  \n";

        if (!empty($messageText)) {
            $chatId = '-517188991';
            $bot = new BotApi(env('TELEGRAM_TOKEN'));

            $bot->setCurlOption(CURLOPT_TIMEOUT, 60);

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
