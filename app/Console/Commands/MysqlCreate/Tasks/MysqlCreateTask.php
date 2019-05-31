<?php

namespace App\Console\Commands\MysqlCreate\Tasks;

use App\Console\Task;

class MysqlCreateTask extends Task
{
    public $name = 'Creating MySQL Database';

    public function handle()
    {
        $sDatabase = $this->shell->mysql()->createDatabase($this->options->database);

        $this->addCustomBinding('databaseSlug', $sDatabase);

        $this->addConclusion('Created new database: ' . $sDatabase);
    }
}
