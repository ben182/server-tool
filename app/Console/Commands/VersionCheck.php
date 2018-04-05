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

    protected function nodejs()
    {
        $sRemoteNodejsVersion = shell_exec('curl -s semver.io/node/stable');
        $sLocalNodejsVersion = shell_exec('node -v');

        // sanitize
        $iRemoteNodejsVersion = intval(preg_replace('/\D/', '', str_replace('.', '', $sRemoteNodejsVersion)));
        $iLocalNodejsVersion = intval(preg_replace('/\D/', '', str_replace('.', '', $sLocalNodejsVersion)));

        if ($iRemoteNodejsVersion > $iLocalNodejsVersion) {
            return $this->info("A new version of Node.js is available ($sRemoteNodejsVersion). Type 'server-tools version:update nodejs' to update to the newest version.");
        }

        return false;
    }
}
