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
     */
    protected function schedule(Schedule $schedule)
    {
        // har roz subah 9 baje followup reminders
        $schedule->command('crm:send-followup-reminders')
                 ->dailyAt('09:00')
                 ->withoutOverlapping()  // agar pehla chal raha ho to doosra mat chalao
                 ->appendOutputTo(storage_path('logs/scheduler.log'));

        // har somwar subah 8 baje weekly report
        $schedule->command('crm:send-weekly-report')
                 ->weeklyOn(1, '08:00')
                 ->appendOutputTo(storage_path('logs/scheduler.log'));

        // har mahine 1 tarikh ko junk cleanup
        $schedule->command('crm:cleanup-junk-leads')
                 ->monthlyOn(1, '00:00')
                 ->appendOutputTo(storage_path('logs/scheduler.log'));
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
