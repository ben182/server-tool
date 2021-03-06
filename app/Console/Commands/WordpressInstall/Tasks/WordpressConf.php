<?php

namespace App\Console\Commands\WordpressInstall\Tasks;

use App\Setting;
use App\Console\Task;

class WordpressConf extends Task
{
    public $name = 'Wordpress Configuration';

    public function handle()
    {
        $email    = $this->options->pioneersConfig ? 'it@brand-pioneers.de' : Setting::getValue('admin_email');
        $password = app('stool-password')->generate();

        $this->shell->exec("cd {$this->bindings->installationDir} && wp core install --url='{$this->bindings->domain->getFullUrl()}' --title='{$this->options->name}' --admin_user=admin --admin_password='$password' --admin_email='$email' --skip-email");

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

            $this->shell->copy(templates_path('wordpress/robots_disallow_all.txt'), $this->bindings->installationDir . '/robots.txt', true);
            $this->shell->exec('chmod -x ' . $this->bindings->installationDir . '/robots.txt');
        }

        if ($this->options->installPlugins) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install wordpress-seo wp-smushit wordfence wps-hide-login updraftplus stops-core-theme-and-plugin-updates --activate");

            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install all-in-one-wp-migration w3-total-cache cloudflare");
        }

        if ($this->options->pioneersConfig) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp option update whl_page {$this->bindings->adminUrl}");
        } else {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin deactivate wps-hide-login");
        }

        $this->addConclusion("Configured Wordpress");
        $this->addConclusion("Email: $email");
        $this->addConclusion("Password: $password");
        $this->addConclusion("Login URL is {$this->bindings->domain->getFullUrl()}" . ($this->options->subDir ? '/' . $this->options->subDir : '') . '/' . $this->bindings->adminUrl);
    }
}
