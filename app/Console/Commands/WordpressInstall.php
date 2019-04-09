<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\ApplicationInstallTaskManager;
use App\Console\ModCommand;

class WordpressInstall extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordpress:install';

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
        parent::handle();

        $sName = $this->ask('Name of Wordpress Site?');

        $sDomain = $this->ask('Domain?');

        $sSubDir = '';
        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask('Which one (relative to ' . $sDomain . '/?');
        }

        (new ApplicationInstallTaskManager([
            'domain' => $sDomain,
            'rootOrSub' => $sRootOrSub,
            'subDir' => $sSubDir,
            'name' => $sName,
        ]))->work();
    }
}
