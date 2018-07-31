<?php

namespace App\Console\Commands\Tasks\Partials;

use App\Console\Commands\Tasks\Task;

class Finish extends Task
{
    public $sName = 'Clean up & Finishing';

    public function requirements()
    {
        return true;
    }

    public function handle()
    {
        fixApachePermissions();
        restartApache();
    }
}
