<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\User;
use App\Stock;
use App\Candle;

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



class GetCandle15MinDayStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getcandle15mindaystock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получаем свечи акций за день с 15 минутным timeframe';

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
        $user = User::select('id')->where('email', 'WyomingCPA@yandex.ru')->first();
        $favorite_ids = $user->favoritesStock->pluck('id')->toArray();
        $stocks = Stock::whereIn('id', $favorite_ids)->orderBy('updated_at')->get();
        foreach ($stocks as $item) {

            //Удаляем старые свечи за день свечи, чтобы график не глючил
            //$deleteCandleRows = Candle::where('tools_id', '=', $item->id)
            //                            ->where('tools_type', 'LIKE', '%stock%')
            //                            ->where('time', '>=', Carbon::now()->subDays(1)->startOfDay())
            //                            ->delete();

            $from = new \DateTime();
            $from->sub(new \DateInterval("P1D"));
            $to = new \DateTime();
            try {
                $candles = $client->getHistoryCandles($item->figi, $from, $to, TIIntervalEnum::MIN15);
            } catch (\Exception $e) {
                echo $e->getMessage();
                continue;
            }

            foreach ($candles as $candle) {
                try {
                    $model = Candle::firstOrCreate(
                        ['tools_id' => $item->id, 'tools_type' => 'stock', 'close' => $candle->getClose() ? $candle->getClose() : 0, 'time' => $candle->getTime()],
                        [
                            'tools_id' => $item->id,
                            'tools_type' => 'stock',
                            'open' => $candle->getOpen() ? $candle->getOpen() : 0,
                            'close' => $candle->getClose() ? $candle->getClose() : 0,
                            'high' => $candle->getHigh() ? $candle->getHigh() : 0,
                            'low' => $candle->getLow() ? $candle->getLow() : 0,
                            'volume' => $candle->getVolume() ? $candle->getVolume() : 0,
                            'time' => $candle->getTime() ? $candle->getTime() : 0,
                            'interval' => $candle->getInterval() ? $candle->getInterval() : 0,
                        ]
                    );
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo $item->figi . "\n";
            $item->touch();
        }
    }
}
