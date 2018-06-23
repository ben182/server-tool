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
    protected $signature = 'php:switch {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $allowedVersions = [
        '5.6',
        '7.0',
        '7.1',
        '7.2',
    ];

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

        if (!in_array($version, $this->allowedVersions)) {
            return $this->abort('Diese Version wird nicht unterstützt');
        }

        shell_exec('bash ' . scripts_path() . 'php/switch-to-php-' . $version . '.sh');
    }
}
