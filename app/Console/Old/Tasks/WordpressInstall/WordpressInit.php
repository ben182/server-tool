<?php

namespace App\Console\Commands\Tasks\WordpressInstall;

use Illuminate\Support\Str;
use App\Console\Commands\Tasks\Task;

class WordpressInit extends Task
{
    public $name = 'Wordpress Init';

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
        copy(templates_path('wordpress/htaccess'), "{$this->bindings->installationDir}/.htaccess");

        $wpconfig = file_get_contents("{$this->bindings->installationDir}/wp-config.php");
        $wpconfig = str_replace('#KEYS#', file_get_contents('https://api.wordpress.org/secret-key/1.1/salt/'), $wpconfig);
        $wpconfig = str_replace('#PREFIX#', Str::random(4) . '_', $wpconfig);

        file_put_contents("{$this->bindings->installationDir}/wp-config.php", $wpconfig);

        $this->shell->cronjob()->create('*/15 * * * *', "php {$this->bindings->installationDir}/wp-cron.php");
    }
}
