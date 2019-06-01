<?php

namespace App\Console\Commands\WordpressInstall\Tasks;

use App\Setting;
use App\Console\Task;
use App\Helper\Password;

class WordpressConf extends Task
{
    public $name = 'Wordpress Configuration';

    public function handle()
    {
        $email    = $this->options->pioneersConfig ? 'it@elbpioneers.de' : Setting::getValue('admin_email');
        $password = app(Password::class)->generate();

        $this->shell->exec("cd {$this->bindings->installationDir} && wp core install --url={$this->bindings->domain->getFullUrl()} --title={$this->options->name} --admin_user=admin --admin_password='$password' --admin_email=$email --skip-email");

        $this->shell->exec("cd {$this->bindings->installationDir} && wp language core install de_DE");
        $this->shell->exec("cd {$this->bindings->installationDir} && wp language core activate de_DE");
        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update timezone_string \"Europe/Berlin\"");
        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update date_format \"j. F Y\"");
        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update time_format \"G:i\"");

        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update permalink_structure '/%postname%/'");

        $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin delete --all");

        if ($this->options->local) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp option update blog_public 0");
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install maintenance --activate");
        }

        if ($this->options->installPlugins) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install all-in-one-seo-pack all-in-one-wp-migration wp-smushit wordfence wps-hide-login --activate");

            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install w3-total-cache");
        }

        if ($this->options->pioneersConfig) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp option update whl_page mp-admin");
        } else {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin deactivate wps-hide-login");
        }

        $this->addConclusion("Configured Wordpress");
        $this->addConclusion("Email: $email");
        $this->addConclusion("Password: $password");
    }
}
