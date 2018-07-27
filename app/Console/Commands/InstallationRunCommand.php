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
        $option = $this->menu('Install phpMyAdmin', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('phpmyadmin', (string) $option === 'yes');

        $option = $this->menu('Install certbot', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('certbot', (string) $option === 'yes');

        $option = $this->menu('Generate SSH key', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('sshKey', (string) $option === 'yes');

        $option = $this->menu('Install Node.js (version-manager)', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('node', (string) $option === 'yes');

        $option = $this->menu('Install yarn', [ // depends on node.js
            'yes',
            'no',
        ])->open();
        editInstalllationKey('yarn', (string) $option === 'yes');

        $option = $this->menu('Install Redis', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('redis', (string) $option === 'yes');

        $option = $this->menu('Install vnStat', [
            'yes',
            'no',
        ])->open();
        editInstalllationKey('vnstat', (string) $option === 'yes');

        foreach (getInstallationConfig() as $key => $value) {
            if ($value !== 'true') {
                continue;
            }
            echo shell_exec('bash ' . scripts_path() . 'partials/' . $key);
        }
    }
}
