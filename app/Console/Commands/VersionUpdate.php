<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VersionUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:update {app}';

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
        echo shell_exec('. ~/.nvm/nvm.sh && nvm install node');
    }

    protected function composer() {
        echo shell_exec('composer self-update');
    }

    protected function nvm() {

        $sDir = getenv('NVM_DIR');
        echo shell_exec('cd ' . $sDir . ' && git fetch origin && git checkout `git describe --abbrev=0 --tags --match "v[0-9]*" $(git rev-list --tags --max-count=1)` && \. "' . $sDir . '/nvm.sh"');
    }
}
