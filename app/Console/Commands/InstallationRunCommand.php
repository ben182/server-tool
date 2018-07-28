<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallationRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $aToInstall = [];

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
        $this->openMenu('phpMyAdmin', 'phpmyadmin');
        $this->openMenu('certbot', 'certbot');
        $this->openMenu('SSH key', 'sshKey');
        $this->openMenu('Node.js (version-manager)', 'node');
        $this->openMenu('yarn', 'yarn');
        $this->openMenu('Redis', 'redis');
        $this->openMenu('vnStat', 'vnstat');

        foreach ($this->aToInstall as $key) {
            echo shell_exec('bash ' . scripts_path() . 'partials/' . $key . '.sh');
        }
    }

    private function openMenu($sTitle, $sKey)
    {
        if (getInstallationConfig()[$sKey] === 'true') {
            return;
        }
        $option = $this->menu('Install ' . $sTitle . '?', [
            'yes',
            'no',
        ])->disableDefaultItems()->open();

        editInstalllationKey($sKey, json_encode($option === 0));

        if ($option === 0) {
            $this->aToInstall[] = $sKey;
        }
    }
}
