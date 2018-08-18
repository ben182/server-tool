<?php

namespace App\Console\Commands\Tasks\MysqlCreate;

use App\Console\Commands\Tasks\Task;

class MysqlCreateTask extends Task
{
    public $sName = 'Creating MySQL Database';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $sDatabase = $this->shell->mysql()->createDatabase($this->oOptions->database);

        $this->addCustomBinding('databaseSlug', $sDatabase);

        $this->addConclusion('Created new database: ' . $sDatabase);
    }
}
