<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DomainController;
use PhpParser\Node\Expr\ShellExec;

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

        $oDomain = new DomainController($sDomain);

        if ($oDomain->doesNotExist()) {
            $this->abort('The domain directory does not exist');
        }

        $sSubDir = '';

        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask("Which one (relative to " . $oDomain->getFullUrl() . "/?");
        }

        $sSymlinkRootDir = '';

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

                $oDomain->createHtmlFolder();

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

        if ($sSubDir) {
            $sSubDir = "/$sSubDir";
        }

        // TODO Laravel specific config and git post hook
        $bLaravel = $this->confirm('Laravel specific config?');

        if ($bLaravel) {
            shell_exec("composer install -d=/var/www/$sDomain/$sGitName");

            copy("/var/www/$sDomain/$sGitName/.env.example", "/var/www/$sDomain/$sGitName/.env");
            replace_string_in_file("/var/www/$sDomain/$sGitName/.env", 'http://localhost', $oDomain->getFullUrl() . $sSubDir);
            shell_exec("php /var/www/$sDomain/$sGitName/artisan key:generate");

            $bDatabase = $this->confirm('Create Database?');
            if ($bDatabase) {
                $sDatabaseName = createMysqlDatabase($sGitName);
                $aUserData = createMysqlUserAndGiveAccessToDatabase($sDatabaseName);

                replace_string_in_file("/var/www/$sDomain/$sGitName/.env", 'DB_DATABASE=homestead', "DB_DATABASE=$sDatabaseName");
                replace_string_in_file("/var/www/$sDomain/$sGitName/.env", 'DB_USERNAME=homestead', 'DB_USERNAME=' . $aUserData['user']);
                replace_string_in_file("/var/www/$sDomain/$sGitName/.env", 'DB_PASSWORD=secret', 'DB_PASSWORD=' . $aUserData['password']);

                // Refresh .env file
                echo shell_exec("php /var/www/$sDomain/$sGitName/artisan config:clear");
                echo shell_exec("service apache2 restart");

                $sMigrateOrSeed = $this->choice('Migrate Or Seed?', ['Migrate', 'Migrate & Seed', 'Nothing']);
                if ($sMigrateOrSeed != 'Nothing') {
                    copy("/var/www/$sDomain/$sGitName/.env", "/var/www/$sDomain/$sGitName/.env2");

                    echo shell_exec("php /var/www/$sDomain/$sGitName/artisan migrate --env=/var/www/$sDomain/$sGitName/.env2");

                    if ($sMigrateOrSeed != 'Migrate & Seed') {
                        echo shell_exec("php /var/www/$sDomain/$sGitName/artisan db:seed --env=/var/www/$sDomain/$sGitName/.env2");
                    }
                }
            }

            /* if ($sRootOrSub == 'Sub') {
                replace_string_in_file("/var/www/$sDomain/$sGitName/public/.htaccess", 'RewriteEngine On', $sSubDir . '/');
            } */

        }else{
            $ComposerInstall = $this->confirm('Composer install in cloned git folder?');

            if ($ComposerInstall) {
                shell_exec("composer install -d=/var/www/$sDomain/$sGitName");
            }
        }

        $bNpmInstall = $this->confirm('NPM install in cloned git folder?');

        if ($bNpmInstall) {
            shell_exec("cd /var/www/$sDomain/$sGitName && npm install");
        }

        $bGitPostPullHook = $this->confirm('Git post pull hook?');

        if ($bGitPostPullHook) {
            copy(templates_path() . 'git/post-merge', "/var/www/$sDomain/$sGitName/.git/hooks/post-merge");
            shell_exec("chmod +x /var/www/$sDomain/$sGitName/.git/hooks/post-merge");
        }

        apache_permissions();

        $this->line("I cloned the repository to /var/www/$sDomain/$sGitName");
        $this->line("Repository Url is " . $oDomain->getFullUrl() . $sSubDir);


    }
}
