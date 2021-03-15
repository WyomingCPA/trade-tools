<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Candle;


class CheckCandleOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-candle-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаляет свечи которым больше 30 дней';

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
        $data = Candle::where('created_at', '<=', Carbon::now()->subDays(30)->toDateTimeString())->delete();
    }
}
