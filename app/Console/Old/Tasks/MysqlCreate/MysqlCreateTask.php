<?php

namespace App\Console\Commands\Tasks\MysqlCreate;

use App\Console\Task;

class MysqlCreateTask extends Task
{
    public $name = 'Creating MySQL Database';

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
        $sDatabase = $this->shell->mysql()->createDatabase($this->options->database);

        $this->addCustomBinding('databaseSlug', $sDatabase);

        $this->addConclusion('Created new database: ' . $sDatabase);
    }
}
