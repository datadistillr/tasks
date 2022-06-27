<?php

namespace CodeIgniter\Tasks\Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\I18n\Time;
use CodeIgniter\Tasks\Models\StoredTaskModel;
use CodeIgniter\Tasks\Scheduler;

class Tasks extends BaseConfig
{
    /*
     * --------------------------------------------------------------------------
     * Database Settings
     * --------------------------------------------------------------------------
     *
     * Database Settings including table name etc.
     */

    /**
     * Test
     */
    public string $test = 'test';

    /**
     * Database Table Name
     * If table name needs to be changed, it should be done prior to running
     * Migrations.
     */
    public string $databaseTable = 'scheduled_tasks';

    /**
     * Date Format.  The 'seconds' are intentionally set to 00
     */
    public string $sqlDateFormat = 'Y-m-d H:i:00';

    /**
     * --------------------------------------------------------------------------
     * Should performance metrics be logged
     * --------------------------------------------------------------------------
     *
     * If true, will log the time it takes for each task to run.
     * Requires the settings table to have been created previously.
     */
    public bool $logPerformance = false;

    /**
     * --------------------------------------------------------------------------
     * Maximum performance logs
     * --------------------------------------------------------------------------
     *
     * The maximum number of logs that should be saved per Task.
     * Lower numbers reduced the amount of database required to
     * store the logs.
     */
    public int $maxLogsPerTask = 10;

    /**
     * Register any tasks within this method for the application.
     * Called by the TaskRunner.
     */
    public function init(Scheduler $schedule)
    {
        $currentTime = new Time();

        $storedTaskModel = new StoredTaskModel();
        $storedTasks     = $storedTaskModel->findByTime($currentTime);

        foreach ($storedTasks as $storedTask) {
            $schedule->{$storedTask->type}($storedTask->command)->cron($storedTask->expression);
        }

        $schedule->command('foo:bar')->daily();

        $schedule->shell('cp foo bar')->daily('11:00 pm');

        $schedule->call(static function () {
            // do something....
        })->mondays()->named('foo');
    }
}
