<?php

namespace App\Console\Commands\Installation;

use App\Helper\Config;
use App\Console\Command;
use App\Helper\Password;
use App\Helper\Domain;

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

    protected $toInstall = [];
    protected $additional = [];


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
        $this->openMenu('Node.js (version-manager)', 'node');
        if ($this->toInstall['node']) {
            $this->openMenu('yarn', 'yarn');
        }
        $this->openMenu('Redis', 'redis');
        $this->openMenu('Netdata', 'netdata', function () {
            $return = [];

            $return['create_floating_ip'] = false;

            $return['standalone'] = $this->confirm('Run standalone version?');

            if (! $return['standalone']) {
                $this->line('Okay it will run in cluster mode');

                $return['master'] = $this->confirm('Is this your master server?');

                $return['master_domain'] = $return['master'] ? $this->ask('What is the Master Servers Domain?') : $this->ask('What is the Master Servers IP?');

                if ($return['master']) {
                    $domain = new Domain($return['master_domain']);

                    $isBoundToThisSystem = $domain->isBoundToThisServer();
                    $isBoundToAFloatingIpOnThisServer = app('stool-floating-ip')->getAllIps()->contains($domain->getARecord());

                    if (!$isBoundToThisSystem && !$isBoundToAFloatingIpOnThisServer) {

                        $return['create_floating_ip'] = $this->confirm('This domain is not bound to your system directly or via a floating ip. Should I create a floating ip for ' . $domain->getARecord() . '?');
                    }
                }

            }

            $return['slack_webhook'] = $this->ask('Slack Notifications Webhook? (leave empty to disable)');
            if ($return['slack_webhook']) {
                $return['slack_recipient'] = $this->ask('Slack Notifications Recipient?');
            }

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

        if ($option === 0) {
            $this->toInstall[] = $sKey;
            $this->config->editInstall($sKey, true);

            if ($callbackWhenYes && $taskManager) {
                $this->additional[] = [
                    'taskmanager' => $taskManager,
                    'options'     => $callbackWhenYes(),
                ];
            }
        }
    }

    protected function installSelectedPartials()
    {
        foreach ($this->toInstall as $sFiles) {
            if ($sFiles === 'node') {
                $this->shell->execScriptAsStool('partials/' . $sFiles);
                continue;
            }

            $this->shell->execScript('partials/' . $sFiles);
        }

        foreach ($this->additional as $additional) {
            $class = $additional['taskmanager'];
            $class::work($additional['options']);
        }
    }
}
