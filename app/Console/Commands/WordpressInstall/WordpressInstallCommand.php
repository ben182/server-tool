<?php

namespace App\Console\Commands\WordpressInstall;

use App\Helper\Apache;
use App\Console\Command;

class WordpressInstallCommand extends Command
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

    protected $apache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Apache $apache)
    {
        parent::__construct();

        $this->apache = $apache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sName = $this->ask('Name of Wordpress Site?');

        $sDomain = $this->choice('Domain?', $this->apache->getAllDomainsEnabled()->toArray());

        $sSubDir    = '';
        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub'], 'Root');
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask('Which one (relative to ' . $sDomain . '/?');
        }

        $bInstallRecommendedPlugins = $this->confirm('Install recommended plugins?', true);
        $bPioneersConfig            = $this->confirm('Apply Pioneers specific config?', true);
        $bLocal                     = $this->confirm('Is this a local or stage site?', true);

        // PAGE BUILDER
        $pageBuilder = $this->choice('Install Page Builder?', [
            'Elementor',
            'None',
        ], 'Elementor');

        WordpressInstallTaskManager::work([
            'domain'         => $sDomain,
            'rootOrSub'      => $sRootOrSub,
            'subDir'         => $sSubDir,
            'name'           => $sName,
            'installPlugins' => $bInstallRecommendedPlugins,
            'pioneersConfig' => $bPioneersConfig,
            'local'          => $bLocal,
            'pageBuilder'    => $pageBuilder,
        ]);
    }
}
