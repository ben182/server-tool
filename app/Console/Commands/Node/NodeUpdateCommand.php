<?php

namespace App\Console\Commands\Node;

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

        $output = $this->shell->exec(". ~/.nvm/nvm.sh && nvm install $version && nvm alias default $version")->getLastOutput();

        // we try to find the switched node version from the output because we can not know the exact version that has been switched to
        if (! preg_match('/(?<=Now using node v)(.*)(?= \()/', $output, $match)) {
            $this->abort('Error in finding version');
        }

        $this->shell->exec('sudo ln -s -f /home/stool/.nvm/versions/node/v' . $match[0] . '/bin/node /usr/local/bin/node');
        $this->shell->exec('sudo ln -s -f /home/stool/.nvm/versions/node/v' . $match[0] . '/bin/npm /usr/local/bin/npm');

        $this->line('Successfully changed Node.js version to ' . $version . '. Please restart the shell!');
    }
}
