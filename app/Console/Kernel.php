<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\DemoCron;
use App\Console\Commands\DataRecordCron;

class Kernel extends ConsoleKernel
{
    /*
    * @var array
    */
    protected $commands = [
        Commands\DemoCron::class,
        Commands\DataRecordCron::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('demo:cron')->everyMinute();
         $schedule->command('order:cron')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
       

        require base_path('routes/console.php');
    }
}
