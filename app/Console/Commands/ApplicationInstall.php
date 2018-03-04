<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplicationInstall extends Command
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
        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask("Which one (relative to /var/www/$sDomain?");
        }
        $sDirectoryOrSymlink = $this->choice('Install in directory or add symlink for a directory', ['directory', 'symlink']);
        if ($sDirectoryOrSymlink === 'symlink') {
            $sSymlinkRootDir = $this->ask("Which source directory?");
            $sSymlinkSymlinkDir = $this->ask("Which symlink directory?");
        }
        $sGit = $this->ask('Which Github repository?');
        $sGitBranch = $this->ask('Which Branch?');

        echo shell_exec("git clone -b $sGitBranch --single-branch $sGit /var/www/$sDomain/ 2>&1");
    }
}
