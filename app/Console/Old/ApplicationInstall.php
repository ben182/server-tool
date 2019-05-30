<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Console\Commands\Tasks\ApplicationInstallTaskManager;

class ApplicationInstall extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:install';

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
        $sDomain = $this->ask('Domain?');

        $sSubDir    = '';
        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask('Which one (relative to ' . $sDomain . '/?');
        }

        $sSymlinkRootDir     = '';
        $sDirectoryOrSymlink = $this->choice('Install in directory or add symlink for a directory', ['directory', 'symlink']);
        if ($sDirectoryOrSymlink === 'symlink') {
            $sSymlinkRootDir = $this->ask('Which source directory?');
        }
        $sGit       = $this->ask('Which Git repository?');
        $sGitBranch = $this->ask('Which Branch?');

        // LARAVEL
        $bLaravel        = $this->confirm('Laravel specific config?');
        $bDatabase       = false;
        $sMigrateOrSeed  = '';
        $bSchedule       = false;
        $ComposerInstall = false;

        if ($bLaravel) {
            $bDatabase = $this->confirm('Create Database?');
            if ($bDatabase) {
                $sMigrateOrSeed = $this->choice('Migrate Or Seed?', ['Migrate', 'Migrate & Seed', 'Nothing']);
            }

            $bSchedule = $this->confirm('Enable running schedule through cronjob?');
        } else {
            $ComposerInstall = $this->confirm('Composer install in cloned git folder?');
        }

        $bNpmInstall = $this->confirm('NPM install in cloned git folder?');

        $bGitPostPullHook = $this->confirm('Git post pull hook?');

        $bGitAutoDeploy = $this->confirm('Git auto deploy?');

        (new ApplicationInstallTaskManager([
            'domain'             => $sDomain,
            'rootOrSub'          => $sRootOrSub,
            'subDir'             => $sSubDir,
            'directoryOrSymlink' => $sDirectoryOrSymlink,
            'symlinkSourceDir'   => $sSymlinkRootDir,
            'git'                => $sGit,
            'branch'             => $sGitBranch,
            'composerInstall'    => $ComposerInstall,
            'laravel'            => $bLaravel,
            'laravel_database'   => $bDatabase,
            'laravel_migrate'    => $sMigrateOrSeed,
            'laravel_cronjob'    => $bSchedule,
            'npmInstall'         => $bNpmInstall,
            'gitPostPullHook'    => $bGitPostPullHook,
            'gad'                => $bGitAutoDeploy,
        ]))->work();
    }
}
