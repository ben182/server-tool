<?php

namespace App\Console\Commands\Installation;

use App\Helper\Config;
use App\Console\Command;
use App\Helper\Password;

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
    protected $password;

    protected $aToInstall = [];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Config $config, Password $password)
    {
        parent::__construct();

        $this->config   = $config;
        $this->password = $password;
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
        $this->openMenu('Netdata', 'netdata', function () {
            $return = [];

            $return['standalone'] = $this->confirm('Run standalone version?');

            if (! $return['standalone']) {
                $this->line('Okay it will run in cluster mode');

                $return['master'] = $this->confirm('Is this your master server?');

                // if (!$return['master']) {
                    $return['master_domain'] = $this->ask('What is the Master Servers Domain?');
                // }
            }

            $return['slack_webhook'] = $this->ask('Slack Notifications Webhook? (leave empty to disable)');

            return $return;
        }, NetdataTaskManager::class);

        $this->installSelectedPartials();
    }

    protected function openMenu($sTitle, $sKey, $callbackWhenYes = null, $taskManager = null)
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

            if ($callbackWhenYes) {
                $options = $callbackWhenYes();

                if ($taskManager) {
                    $taskManager::work($options);
                }
            }
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
