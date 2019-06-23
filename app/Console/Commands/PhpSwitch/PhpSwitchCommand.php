<?php

namespace App\Console\Commands\PhpSwitch;

use App\Console\Command;

class PhpSwitchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'php:switch
                            {version : The version to switch to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switches the PHP Version on the Apache Server and the CLI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $version = $this->argument('version');

        if (! in_array($version, $this->getAvailablePhpVersions())) {
            return $this->abort('This version is not installed on your system');
        }

        $this->shell->execScript('php/switch-to-php-' . $version);
    }

    protected function getAvailablePhpVersions()
    {
        $aAvailableFiles = glob("/etc/apache2/conf-available/php*-fpm.conf");

        $aVersions = [];
        for ($i = 0; $i < count($aAvailableFiles); $i++) {
            $aVersions[] = getStringBetween($aAvailableFiles[$i], '/php', '-fpm.conf');
        }

        return $aVersions;
    }
}
