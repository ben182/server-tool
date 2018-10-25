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

        copy(templates_path('supervisor.conf'), $sNewFileName);
        replace_string_in_file($sNewFileName, '#PROGRAM#', str_slug($this->oOptions->name));
        replace_string_in_file($sNewFileName, '#COMMAND#', $this->oOptions->command);
        $this->shell->exec('sudo supervisorctl reread');
        $this->shell->exec('sudo supervisorctl update');
    }
}
