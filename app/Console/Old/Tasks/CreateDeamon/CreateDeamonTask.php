<?php

namespace App\Console\Commands\Tasks\CreateDeamon;

use App\Console\Task;

class CreateDeamonTask extends Task
{
    public $name = 'Creating Deamon';

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
        $sNewFileName = '/etc/supervisor/conf.d/' . str_slug($this->options->name) . '.conf';

        $this->shell->copy(templates_path('supervisor.conf'), $sNewFileName);
        $this->shell->replaceStringInFile('#PROGRAM#', str_slug($this->options->name), $sNewFileName);
        $this->shell->replaceStringInFile('#COMMAND#', $this->options->command, $sNewFileName);

        $this->shell->exec('sudo supervisorctl reread');
        $this->shell->exec('sudo supervisorctl update');
    }
}
