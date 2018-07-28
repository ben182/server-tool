<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SwitchPhpVersion extends Command
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
    protected $description = 'Switches the PHP Version on your Apache Server and the CLI';

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
            return $this->abort('This version is not installed on your system.');
        }

        quietCommand('bash ' . scripts_path() . 'php/switch-to-php-' . $version . '.sh');
    }

    private function getAvailablePhpVersions()
    {
        $aAvailableFiles = glob("/etc/apache2/mods-available/php*.load");

        $aVersions = [];
        for ($i = 0; $i < $aAvailableFiles; $i++) {
            $aVersions[] = getStringBetween($aAvailableFiles[$i], '/php', '.load');
        }
        return $aVersions;
    }
}
