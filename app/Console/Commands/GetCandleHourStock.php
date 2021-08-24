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

class GetCandleHourStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'command:get-hour-candle-stock';

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
        $client = new TIClient(env('TOKEN_TINKOFF'), TISiteEnum::EXCHANGE);
        $stocks = Stock::where('is_dividend', '=', true)->get();
        foreach ($stocks as $item) {

            //Удаляем старые свечи за день свечи, чтобы график не гючил
            $deleteCandleRows = Candle::where('tools_id', '=', $item->id)
                ->where('tools_type', 'LIKE', '%stock%')
                ->where('interval', '=', 'hour')
                ->where('time', '>=', Carbon::now()->subDays(1)->startOfDay())
                ->delete();

            $from = new \DateTime();
            $from->sub(new \DateInterval("P7D"));
            $to = new \DateTime();
            try {
                $candles = $client->getHistoryCandles($item->figi, $from, $to, TIIntervalEnum::HOUR);
            } catch (Exception $e) {
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
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo $item->figi . "\n";
            $item->touch();
        }
        return 0;
    }
}
