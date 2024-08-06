<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Pools;
use App\SpotPools;

class SpotPoolsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:spotpools';

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
        $today_pools_last_summ = null;
        $today_pools = Pools::whereDate('created_at',  Carbon::today());
        $last_pools_30_min = Pools::where('created_at', '>', Carbon::now()->subMinutes(32)->toDateTimeString())->get()->unique('name')->toArray();
        if ($today_pools->count() !== 0) {
            $today_pools_first_time = $today_pools->first()->created_at;
            $today_pools_last_time = $today_pools->orderBy('created_at', 'DESC')->first()->created_at;
            $today_pools_last_summ = Pools::whereBetween('created_at', [Carbon::parse($today_pools_last_time)->subMinutes(2), Carbon::parse($today_pools_last_time)->addMinutes(2)])->get();


            $model = SpotPools::create([
                'total_balances' => $today_pools_last_summ?->unique('name')->sum('balances'),
                'count_pools' => count($last_pools_30_min),
            ]);
        }


        return 0;
    }
}
