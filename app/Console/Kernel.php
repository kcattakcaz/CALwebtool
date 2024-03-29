<?php

namespace CALwebtool\Console;

use CALwebtool\FormDefinition;
use CALwebtool\Http\Controllers\FormDefinitionController;
use CALwebtool\Http\Controllers\ScoreController;
use CALwebtool\Http\Controllers\SubmissionController;
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
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*$schedule->command('inspire')
                 ->hourly();

        $schedule->call(function(){
            SubmissionController::notify();
        });

        */

        $schedule->call(function () {
            FormDefinitionController::scheduleForms();
        })->everyMinute();

        $schedule->call(function(){
           ScoreController::autoSubmissionStatus();
        })->everyMinute();
    }
}
