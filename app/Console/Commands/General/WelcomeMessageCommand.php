<?php

namespace App\Console\Commands\General;

use App\Console\Stool;
use Illuminate\Console\Command;

class WelcomeMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stool:welcome-message';

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
        $this->line('This server was provisioned by stool | created by Benjamin Bortels');
        $this->line('stool v' . Stool::version());

        if (Stool::updateAvailable()) {
            $this->error('An update for stool is available (' . Stool::versionOnRemote() . ')');
        }

        $this->break();
    }
}
