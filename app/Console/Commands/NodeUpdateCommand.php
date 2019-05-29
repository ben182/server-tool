<?php

namespace App\Console\Commands;

use App\Console\Command;

class NodeUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'node:update {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Node.js to a specified version';

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
        $version = $this->argument('version');

        $this->shell->exec("nvm install $version");
        $this->shell->exec("nvm use $version");
        $this->shell->exec('sudo ln -s "$(which node)" /usr/local/bin/node');
    }
}
