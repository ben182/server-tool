<?php

namespace App\Console\Commands\Domains;

use App\Helper\Apache;
use Illuminate\Console\Command;

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
        $this->apache->getAllDomainsEnabled()->each(function ($domain) {
            $this->info($domain);
        });
    }
}
