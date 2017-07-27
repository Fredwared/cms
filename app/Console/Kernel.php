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
        Commands\ClearLog::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //run at night to restart job
        $schedule->command('queue:listen --timeout=0')->everyMinute()->withoutOverlapping();

        //run every day to clear log
        $schedule->command('log:clearlog')->dailyAt('0:0')->sendOutputTo(storage_path('/logs/clear_log.log'))->emailOutputTo('doquyettien@gmail.com', true);

        //delete all file im folder tmp
        $schedule->exec('rm -rf ' . storage_path('cloud') . '/tmp/*')->weeklyOn(1, '0:0');

        //delete log file
        $schedule->exec('rm -f ' . storage_path('logs') . '/*.log')->weeklyOn(1, '0:0');

        //get category from vne
        //$schedule->command('crawler:categoryvne')->dailyAt('23:00');

        //get article from vne
        //$schedule->command('crawler:articlevne')->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
