<?php

namespace App\Console\Commands\Installation;

use App\Helper\Config;
use App\Console\Command;

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

    protected $aToInstall = [];
    protected $bindings = [];
    protected $callbacksAfterInstallation = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Config $config)
    {
        parent::__construct();

        $this->config = $config;
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
        $this->openMenu('Netdata', 'netdata', function() {
            $this->bindings['netdata']['standalone'] = $this->confirm('Run standalone version?');

            if (!$this->bindings['netdata']['standalone']) {
                $this->line('Okay it will run in cluster mode');

                $this->bindings['netdata']['master'] = $this->confirm('Is this your master server?');
            }

            $this->bindings['netdata']['slack_webhook'] = $this->ask('Slack Notifications Webhook? (leave empty to disable)');
        }, function() {
            $this->shell->replaceStringInFile('SLACK_WEBHOOK_URL=""', 'SLACK_WEBHOOK_URL="' . $this->bindings['netdata']['slack_webhook'] . '"', '/etc/netdata/health_alarm_notify.conf');
        });

        $this->installSelectedPartials();
    }

    protected function openMenu($sTitle, $sKey, $callbackWhenYes = null, $callbackAfterInstallation = null)
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
                $callbackWhenYes();
            }

            if ($callbackAfterInstallation) {
                $this->callbacksAfterInstallation[] = $callbackAfterInstallation;
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

        foreach ($this->callbacksAfterInstallation as $callback) {
            $callback();
        }
    }
}
