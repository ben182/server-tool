<?php

namespace App\Console\Commands\VhostBasicAuth\Tasks;

use App\Setting;
use App\Console\Task;

class VhostBasicAuthTask extends Task
{
    public $name = 'Modifying vHost';

    public function handle()
    {
        $this->shell->exec("sudo touch /home/stool/.stool/{$this->bindings->domain}/.htpasswd");
        $this->shell->exec("sudo htpasswd -b /home/stool/.stool/{$this->bindings->domain}/.htpasswd {$this->options->user} {$this->options->password}");
    }
}
