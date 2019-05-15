<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;
use Illuminate\Support\Str;

class WordpressConf extends Task
{
    public $sName = 'Wordpress Configuration';

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
        $email = $this->oOptions->pioneersConfig ? 'it@elbpioneers.de' : Setting::where('key', 'admin_email')->value('value');
        $password = Str::random(random_int(14, 20));
        $this->shell->exec("cd {$this->bindings->installationDir} && wp core install --url={$this->bindings->domain->getFullUrl()} --title={$this->oOptions->name} --admin_user=admin --admin_password='$password' --admin_email=$email");

        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update permalink_structure '/%postname%/'");

        if ($this->oOptions->installPlugins) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install all-in-one-seo-pack all-in-one-wp-migration wp-smushit wordfence wps-hide-login --activate --quiet");

            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install w3-total-cache --quiet");
        }

        if ($this->oOptions->pioneersConfig) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp option update whl_page mp-admin --quiet");
        }

        $this->addConclusion("Configured Wordpress");
        $this->addConclusion("Email: $email");
        $this->addConclusion("Password: $password");
    }
}
