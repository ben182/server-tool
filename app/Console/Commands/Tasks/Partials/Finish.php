<?php

namespace App\Console\Commands\Tasks\Partials;

use App\Console\Commands\Tasks\SubBaseTask;

class Finish extends SubBaseTask
{
    public $sName = 'Clean up & Finishing';

    public function requirements()
    {
        return true;
    }

    public function handle()
    {
        try {
            fixApachePermissions();
            restartApache();
        } catch (\Exception $e) {
            echo $e;
            return false;
        }

        return true;
    }
}
