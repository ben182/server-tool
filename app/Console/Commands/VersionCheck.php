<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VersionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:check {app}';

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
        $sApp = $this->argument('app');
        call_user_func([$this, $sApp]);
    }

    /**
     * Sanitizes a given version. v2.1.0 => 210
     *
     * @param string $sVersion
     * @return integer
     */
    protected function sanitizeVersion($sVersion) {
        return intval(preg_replace('/\D/', '', str_replace('.', '', $sVersion)));
    }

    /**
     * Gets the latest version of a Github repo
     *
     * @param string $sOwner
     * @param string $sRepo
     * @return mixed The version. False in case of failure
     */
    public function githubGetLatestVersion($sOwner, $sRepo) {
        $aReturn = json_decode(file_get_contents("https://api.github.com/repos/$sOwner/$sRepo/releases/latest"), true);
        if (!isset($aReturn['tag_name'])) {
            return false;
        }
        return $aReturn['tag_name'];
    }

    protected function composer() {
        $this->line('Checking for new Composer version...');
        $sRemoteVersion = $this->githubGetLatestVersion('composer', 'composer');
        $sLocalVersion = preg_replace('/\D/', '', shell_exec('composer -V'));

        if (str_contains($sLocalVersion, 'command not found')) {
            return false;
        }

        // sanitize
        $iRemoteVersion = $this->sanitizeVersion($sRemoteVersion);
        $iLocalVersion = $this->sanitizeVersion($sLocalVersion);

        if ($iRemoteVersion > $iLocalVersion) {
            return $this->line("A new version of Composer is available ($sRemoteVersion). Type 'server-tools version:update composer' to update to the newest version.");
        }

        $this->line('You use the latest version (' . $sLocalVersion . ')');
        return false;
    }

    protected function nodejs()
    {
        $this->line('Checking for new Node.js version...');
        $sRemoteNodejsVersion = shell_exec('curl -s semver.io/node/stable');
        $sLocalNodejsVersion = shell_exec('node -v');

        // remove line breaks
        $sRemoteNodejsVersion = str_replace(array("\r", "\n"), '', $sRemoteNodejsVersion);
        $sLocalNodejsVersion = str_replace(array("\r", "\n"), '', $sLocalNodejsVersion);

        if (str_contains($sLocalNodejsVersion, 'command not found')) {
            return false;
        }

        // sanitize
        $iRemoteNodejsVersion = intval(preg_replace('/\D/', '', str_replace('.', '', $sRemoteNodejsVersion)));
        $iLocalNodejsVersion = intval(preg_replace('/\D/', '', str_replace('.', '', $sLocalNodejsVersion)));

        if ($iRemoteNodejsVersion > $iLocalNodejsVersion) {
            return $this->line("A new version of Node.js is available ($sRemoteNodejsVersion). Type 'server-tools version:update nodejs' to update to the newest version.");
        }

        $this->line('You use the latest version (' . $sLocalNodejsVersion . ')');
        return false;
    }
}
