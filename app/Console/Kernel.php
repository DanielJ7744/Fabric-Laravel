<?php

namespace App\Console;

use App\Jobs\DisableServicesForExpiredTrials;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new DisableServicesForExpiredTrials)->daily();

        $schedule->command('alerts:schedule')->withoutOverlapping()->everyMinute();
        $schedule->command('alerts:send')->withoutOverlapping()->everyMinute();
        $schedule->command('alerts:transactions')
            ->withoutOverlapping()
            ->cron(env('CRON_ALERT_TRANSACTIONS', '0 8 * * *'));
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
