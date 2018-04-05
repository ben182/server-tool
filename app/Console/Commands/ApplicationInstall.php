<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Helper\Domain;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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

        $oDomain = new Domain($sDomain);

        if ($oDomain->doesNotExist()) {
            $this->abort('The domain directory does not exist');
        }

        $aReturn = [];

        $sSubDir = '';

        $sRootOrSub = $this->choice('Root or Subdirectory?', ['Root', 'Sub']);
        if ($sRootOrSub === 'Sub') {
            $sSubDir = $this->ask('Which one (relative to ' . $oDomain->getFullUrl() . '/?');
        }

        $sSymlinkRootDir = '';

        $sDirectoryOrSymlink = $this->choice('Install in directory or add symlink for a directory', ['directory', 'symlink']);
        if ($sDirectoryOrSymlink === 'symlink') {
            $sSymlinkRootDir = $this->ask('Which source directory?');
        }
        $sGit = $this->ask('Which Github repository?');
        $sGitBranch = $this->ask('Which Branch?');
        $sGitName = getStringBetween($sGit, '/', '.git');

        $this->task('Cloning Repository', function () use ($sDomain, $sGitBranch, $sGit) {
            try {
                shell_exec("cd /var/www/$sDomain && git clone -b $sGitBranch $sGit");
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

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

        $this->addToReturn('[APPLICATION]');
        $this->addToReturn("I cloned the repository to /var/www/$sDomain/$sGitName");
        $this->addToReturn('Repository Url is ' . $oDomain->getFullUrl() . $sSubDir);

        // LARAVEL
        $bLaravel = $this->confirm('Laravel specific config?');

        if ($bLaravel) {
            shell_exec("composer install -d=/var/www/$sDomain/$sGitName");

            copy("/var/www/$sDomain/$sGitName/.env.example", "/var/www/$sDomain/$sGitName/.env");
            editEnvKey("/var/www/$sDomain/$sGitName/.env", 'APP_URL', $oDomain->getFullUrl() . $sSubDir);

            echo shell_exec("cd /var/www/$sDomain/$sGitName && sudo php artisan key:generate");

            $bDatabase = $this->confirm('Create Database?');
            if ($bDatabase) {
                $sDatabaseName = createMysqlDatabase($sGitName);
                $aUserData = createMysqlUserAndGiveAccessToDatabase($sDatabaseName);

                editEnvKey("/var/www/$sDomain/$sGitName/.env", 'DB_DATABASE', $sDatabaseName);
                editEnvKey("/var/www/$sDomain/$sGitName/.env", 'DB_USERNAME', $aUserData['user']);
                editEnvKey("/var/www/$sDomain/$sGitName/.env", 'DB_PASSWORD', $aUserData['password']);

                $sMigrateOrSeed = $this->choice('Migrate Or Seed?', ['Migrate', 'Migrate & Seed', 'Nothing']);
                if ($sMigrateOrSeed != 'Nothing') {
                    echo shell_exec("cd /var/www/$sDomain/$sGitName && sudo php artisan migrate");

                    if ($sMigrateOrSeed == 'Migrate & Seed') {
                        echo shell_exec("cd /var/www/$sDomain/$sGitName && sudo php artisan db:seed");
                    }
                }

                $this->addToReturn('[DB]');
                $this->addToReturn('Database: ' . $sDatabaseName);
                $this->addToReturn('User: ' . $aUserData['user']);
                $this->addToReturn('Password: ' . $aUserData['password']);
            }

            $bSchedule = $this->confirm('Enable running schedule through cronjob?');

            if ($bSchedule) {
                echo shell_exec("crontab -l | { cat; echo \"* * * * * /var/www/$sDomain/$sGitName/artisan schedule:run >> /dev/null 2>&1\"; } | crontab -");
            }
        } else {
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

        $bGitAutoDeploy = $this->confirm('Git auto deploy?');
        if ($bGitAutoDeploy) {
            $this->addToReturn('[GIT AUTO DEPLOY]');
            $this->call('gad:add', [
                '--dir' => "$sDomain/$sGitName",
                '--branch' => $sGitBranch,
                '--nooutput' => true,
            ]);
        }

        $this->task('Clean up & Finishing', function () {
            try {
                $this->fixApachePermissions();
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            return true;
        });

        echo $this->getReturn();
    }
}
