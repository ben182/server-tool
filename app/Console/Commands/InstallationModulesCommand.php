<?php

namespace App\Console\Commands;

use App\Helper\Config;
use App\Helper\Shell\Shell;
use Illuminate\Console\Command;

class InstallationModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $config;
    protected $shell;

    protected $aToInstall = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Config $config, Shell $shell)
    {
        parent::__construct();

        $this->config = $config;
        $this->shell  = $shell;
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

        $this->installSelectedPartials();
    }

    protected function openMenu($sTitle, $sKey)
    {
        if ($this->config->isInstalled($sKey)) {
            return;
        }

        $option = $this->menu('Install ' . $sTitle . '?', [
            'yes',
            'no',
        ])->disableDefaultItems()->open();

        $this->config->editInstall($sKey, $option === 0);

        if ($option === 0) {
            $this->aToInstall[] = $sKey;
        }
    }

    protected function installSelectedPartials()
    {
        foreach ($this->aToInstall as $sFiles) {
            if ($sFiles === 'node') {
                $this->shell->execScriptAsStool('partials/' . $sFiles);
                continue;
            }
            $this->shell->execScript('partials/' . $sFiles);
        }
    }
}
