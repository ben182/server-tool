<?php

namespace App\Console\Commands\Partials;

use App\Console\Task;

class RestartApacheTask extends Task
{
    public $name = 'Restarting Apache Server';

    public function handle()
    {
        $this->shell->service()->restart('apache2');

        $this->addConclusion('Restarted Apache Server');
    }
}
