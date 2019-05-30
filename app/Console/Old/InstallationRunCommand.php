<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use Illuminate\Console\Command;

class InstallationRunCommand extends ModCommand
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
        $this->openMenu('Node.js (version-manager)', 'node');
        $this->openMenu('yarn', 'yarn');
        $this->openMenu('Redis', 'redis');
        $this->openMenu('vnStat', 'vnstat');

        foreach ($this->aToInstall as $sFiles) {
            if ($sFiles === 'node') {
                $this->shell->execScriptAsStool('partials/' . $sFiles);
                continue;
            }
            $this->shell->execScript('partials/' . $sFiles);
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

        editInstallationKey($sKey, json_encode($option === 0));

        if ($option === 0) {
            $this->aToInstall[] = $sKey;
        }
    }
}
