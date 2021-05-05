<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\GetAllBonds',
        'App\Console\Commands\GetAllEtf',
        'App\Console\Commands\Stocks',
        'App\Console\Commands\CheckIntradayIndicator',
        'App\Console\Commands\GetCandle5MinDayStock',
        'App\Console\Commands\GetCandleHourEtf',
        'App\Console\Commands\GetCandleBond',
        'App\Console\Commands\SendToTelegrammStateProfile',
        'App\Console\Commands\Stocks',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:telegrammstateprofile')
            ->weekdays()
            ->hourlyAt(15)
            ->timezone('Europe/Moscow')
            ->between('10:00', '19:00');

        $schedule->command('command:getcandlebond')
            ->weekdays()
            ->everyThirtyMinutes()
            ->timezone('Europe/Moscow')
            ->between('10:00', '19:00');

        $schedule->command('command:getcandle15minday')
            ->weekdays()
            ->everyTenMinutes()
            ->timezone('Europe/Moscow')
            ->between('10:00', '19:00');

        $schedule->command('command:checkemaindicator')
            ->weekdays()
            ->everyTenMinutes()
            ->timezone('Europe/Moscow')
            ->between('10:00', '19:00');

        $schedule->command('command:bond')
            ->weekdays()
            ->dailyAt('11:00')
            ->timezone('Europe/Moscow')
            ->between('10:00', '19:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
