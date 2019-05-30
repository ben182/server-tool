<?php

namespace App\Console\Commands\DeamonCreate\Tasks;

use App\Console\Task;
use Illuminate\Support\Str;

class DeamonCreateTask extends Task
{
    public $name = 'Creating Deamon';

    public function handle()
    {
        $sNewFileName = '/etc/supervisor/conf.d/' . Str::slug($this->options->name) . '.conf';

        $this->shell->copy(templates_path('supervisor.conf'), $sNewFileName);
        $this->shell->replaceStringInFile('#PROGRAM#', Str::slug($this->options->name), $sNewFileName);
        $this->shell->replaceStringInFile('#COMMAND#', $this->options->command, $sNewFileName);

        $this->shell->exec('sudo supervisorctl reread');
        $this->shell->exec('sudo supervisorctl update');
    }
}
