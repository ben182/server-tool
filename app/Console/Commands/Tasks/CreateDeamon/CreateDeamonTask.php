<?php

namespace App\Console\Commands\Tasks\CreateDeamon;

use App\Console\Commands\Tasks\Task;

class CreateDeamonTask extends Task
{
    public $sName = 'Creating Deamon';

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
        $sNewFileName = '/etc/supervisor/conf.d/' . str_slug($this->oOptions->name) . '.conf';

        $this->shell->copy(templates_path('supervisor.conf'), $sNewFileName);
        $this->shell->replaceStringInFile('#PROGRAM#', str_slug($this->oOptions->name), $sNewFileName);
        $this->shell->replaceStringInFile('#COMMAND#', $this->oOptions->command, $sNewFileName);

        $this->shell->exec('sudo supervisorctl reread');
        $this->shell->exec('sudo supervisorctl update');
    }
}
