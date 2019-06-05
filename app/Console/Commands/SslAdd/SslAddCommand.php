<?php

namespace App\Console\Commands\SslAdd;

use App\Console\Command;
use App\Helper\Apache;

class SslAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds an SSL certificate';

    protected $apache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Apache $apache)
    {
        parent::__construct();

        $this->apache = $apache;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $domain = $this->anticipate('Domain?', $this->apache->getAllDomainsEnabled());

        $htaccess = $this->confirm('Non SSL to SSL Htaccess?');

        SslAddTaskManager::work([
            'domain'   => $domain,
            'htaccess' => $htaccess,
        ]);
    }
}
