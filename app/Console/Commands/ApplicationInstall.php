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

        if (!file_exists("/var/www/$sDomain")) {
            $this->error('The domain directory does not exist');
            return;
        }

        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask("Which one (relative to /var/www/$sDomain/html/?");
        }
        $sDirectoryOrSymlink = $this->choice('Install in directory or add symlink for a directory', ['directory', 'symlink']);
        if ($sDirectoryOrSymlink === 'symlink') {
            $sSymlinkRootDir = $this->ask("Which source directory?");
        }
        $sGit = $this->ask('Which Github repository?');
        $sGitBranch = $this->ask('Which Branch?');

        echo shell_exec("cd /var/www/$sDomain && git clone -b $sGitBranch $sGit 2>&1");

        $sGitName = getStringBetween($sGit, '/', '.git');

        switch ($sRootOrSub) { // TODO clean up
            case 'Root':

                if (file_exists("/var/www/$sDomain/html")) {
                    shell_exec("mv /var/www/$sDomain/html /var/www/$sDomain/html2");
                }

                if ($sDirectoryOrSymlink == 'directory') {

                    shell_exec("ln -s /var/www/$sDomain/$sGitName /var/www/$sDomain/html");
                }

                if ($sDirectoryOrSymlink == 'symlink') {

                    shell_exec("ln -s /var/www/$sDomain/$sGitName/$sSymlinkRootDir /var/www/$sDomain/html");
                }
                break;
            case 'Sub':

                if (!file_exists("/var/www/$sDomain/html")) {
                    mkdir("/var/www/$sDomain/html", 755, TRUE);
                }

                if ($sDirectoryOrSymlink == 'directory') {
                    shell_exec("ln -s /var/www/$sDomain/$sGitName /var/www/$sDomain/html/$sSubDir");
                }

                if ($sDirectoryOrSymlink == 'symlink') {
                    shell_exec("ln -s /var/www/$sDomain/$sGitName/$sSymlinkRootDir /var/www/$sDomain/html/$sSubDir");
                }
                break;
            default:
                break;
        }

        apache_permissions();

        $this->line("I cloned the repository to /var/www/$sDomain/$sGitName");
    }
}
