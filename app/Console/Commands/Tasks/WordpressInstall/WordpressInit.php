<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use App\Console\Commands\Tasks\Task;
use Illuminate\Support\Str;

class WordpressInit extends Task
{
    public $sName = 'Wordpress Init';

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
        copy(templates_path('wordpress/wp-config.php'), "{$this->bindings->installationDir}/wp-config.php");

        $wpconfig = file_get_contents("{$this->bindings->installationDir}/wp-config.php");
        $wpconfig = str_replace('#KEYS#', file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/'), $wpconfig);
        $wpconfig = str_replace('#PREFIX#', Str::random(4) . '_', $wpconfig);

        file_put_contents("{$this->bindings->installationDir}/wp-config.php", $wpconfig);

        $this->shell->cronjob()->create('*/15 * * * *', "php {$this->bindings->installationDir}/wp-cron.php >/dev/null 2>&1");

        $this->shell->exec("cd {$this->bindings->installationDir} && wp option update permalink_structure '/%postname%/'");

        if ($this->oOptions->installPlugins) {
            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install all-in-one-seo-pack all-in-one-wp-migration wp-smushit wordfence wps-hide-login --activate --quiet");

            $this->shell->exec("cd {$this->bindings->installationDir} && wp plugin install w3-total-cache --quiet");

            $this->shell->exec("cd {$this->bindings->installationDir} && wp option update whl_page mp-admin --quiet");
        }

        $this->addConclusion("Configured Wordpress");
    }
}
