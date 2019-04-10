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

        $this->addConclusion("Configured Wordpress");
    }
}
