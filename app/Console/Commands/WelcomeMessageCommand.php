<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Stool;

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
        $this->line('This server was provisioned by stool | Developed by Benjamin Bortels');
        $this->line('stool v' . Stool::version());
    }
}
