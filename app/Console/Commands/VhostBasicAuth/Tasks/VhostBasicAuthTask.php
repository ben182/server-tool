<?php

namespace App\Console\Commands\VhostBasicAuth\Tasks;

use App\Console\Task;

class VhostBasicAuthTask extends Task
{
    public $name = 'Modifying vHost';

    public function handle()
    {
        $htpasswd = "/home/stool/.stool/{$this->bindings->domain}/.htpasswd";

        $this->shell->exec("sudo mkdir -p /home/stool/.stool/{$this->bindings->domain}");
        $this->shell->exec("sudo touch $htpasswd");
        $this->shell->exec("sudo htpasswd -b $htpasswd {$this->options->user} {$this->options->password}");

        $this->shell->replaceStringInFile('Require all granted', 'AuthType Basic\\nAuthName "Restricted Content"\\nAuthUserFile ' . $htpasswd . '\\nRequire valid-user\\n# Require all granted', $this->bindings->domain->getApacheSite());

        if ($this->bindings->domain->isSSL()) {
            $this->shell->replaceStringInFile('Require all granted', 'AuthType Basic\\nAuthName "Restricted Content"\\nAuthUserFile ' . $htpasswd . '\\nRequire valid-user\\n# Require all granted', $this->bindings->domain->getApacheSslSite());
        }
    }
}
