<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('scrape --count=5')->everyTwoMinutes()->runInBackground();
        $schedule->command('scrape:ekonomi --count=5')->everyTwoMinutes()->runInBackground();
        $schedule->command('scrape:hiburan --count=5')->everyTwoMinutes()->runInBackground();
        $schedule->command('scrape:hukum --count=5')->everyTwoMinutes()->runInBackground();
        $schedule->command('scrape:kesehatan --count=5')->everyTwoMinutes()->runInBackground();
        // $schedule->command('scrape:kuliner --count=5')->everyTwoMinutes()->runInBackground();
        // $schedule->command('scrape:olahraga --count=5')->everyTwoMinutes()->runInBackground();
        // $schedule->command('scrape:otomotif --count=5')->everyTwoMinutes()->runInBackground();
        // $schedule->command('scrape:pendidikan --count=5')->everyTwoMinutes()->runInBackground();
        $schedule->command('scrape:politik --count=5')->everyTwoMinutes()->runInBackground();
        // $schedule->command('scrape:teknologi --count=5')->everyTwoMinutes()->runInBackground();
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
