<?php

namespace App\Console\Commands\General;

use App\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates stool';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->shell->exec('cd /etc/stool && sudo git pull');
    }
}
