<?php

namespace App\Console\Commands\Domains;

use Illuminate\Console\Command;
use App\Helper\Apache;

class DomainsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists all domains linked to this server';

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
        foreach ($this->apache->getAllDomainsEnabled() as $domain) {
            $this->info($domain);
        }
    }
}
