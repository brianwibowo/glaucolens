<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        
        // Run project file cleanup weekly
        $schedule->command('project:cleanup-files')->weekly()->sundays()->at('01:00')
            ->appendOutputTo(storage_path('logs/project-cleanup.log'));
            
        // Update PKL statuses daily
        $schedule->command('pkl:update-status')->dailyAt('00:15')
            ->appendOutputTo(storage_path('logs/pkl-status-update.log'));
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
