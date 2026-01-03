<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Generate weekly seances every Sunday at 12:00
        $schedule->command('seances:generate-weekly')->weeklyOn(0, '12:00');

        // Mark missed seances every night at 23:59
        $schedule->command('seances:mark-missed')->dailyAt('23:59');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
