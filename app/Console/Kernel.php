<?php

namespace App\Console;

use App\Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Schema;

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
        if (env('DB_DATABASE') === 'homestead' || ! Schema::hasTable('tasks')) {
            return;
        }

        $tasks = Task::all();

        // Go through each task to dynamically set them up.
        foreach ($tasks as $task) {
            $aParameters = [];
            foreach ($task->parameter as $param => $value) {
                if ($value === false || $value === '') {
                    continue;
                }
                if ($value === true) {
                    $aParameters[] = "$param";
                    continue;
                }

                $aParameters[] = "$param=$value";
            }
            $aParameters[] = '-n';

            call_user_func_array([$schedule->command($task->command . ' ' . implode(' ', $aParameters)), $task->frequency], $task->frequency_parameter);
        }
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
