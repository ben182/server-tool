<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\DeleteVhostTaskManager;
use App\Console\ModCommand;
use Illuminate\Console\Command;

class DeleteVhostCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:delete';

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
        parent::handle();

        $sDomain = $this->ask('Domain?');

        $bDeleteDir = $this->confirm("Delete /var/www/$sDomain?", 0);

        (new DeleteVhostTaskManager([
            'domain'             => $sDomain,
            'deleteDomainFolder' => $bDeleteDir,
        ]))->work();
    }
}
