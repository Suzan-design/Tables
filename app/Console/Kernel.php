<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use app\Jobs\UpdateRestaurantTimes;
use app\Console\Commands\GenerateMonthlyWorkTimes;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
     protected $commands = [
    \App\Console\Commands\GenerateMonthlyWorkTimes::class,
];
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->job(new UpdateRestaurantTimes())->dailyAt('12:00');
        $schedule->command('worktimes:generate')->dailyAt('12:00');
        $schedule->command('subscriptions:check')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
