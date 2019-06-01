<?php

namespace App\Console\Commands\WordpressInstall;

use App\Console\Command;

class WordpressInstall extends Command
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
        $sName = $this->ask('Name of Wordpress Site?');

        $sDomain = $this->ask('Domain?');

        $sSubDir    = '';
        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask('Which one (relative to ' . $sDomain . '/?');
        }

        $bInstallRecommendedPlugins = $this->confirm('Install recommended plugins?', true);
        $bPioneersConfig            = $this->confirm('Apply Pioneers specific config?', true);
        $bLocal                     = $this->confirm('Is this a local or stage site?', true);

        WordpressInstallTaskManager::work([
            'domain'         => $sDomain,
            'rootOrSub'      => $sRootOrSub,
            'subDir'         => $sSubDir,
            'name'           => $sName,
            'installPlugins' => $bInstallRecommendedPlugins,
            'pioneersConfig' => $bPioneersConfig,
            'local'          => $bLocal,
        ]);
    }
}
