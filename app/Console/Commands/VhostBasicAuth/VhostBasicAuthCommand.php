<?php

namespace App\Console\Commands\VhostBasicAuth;

use App\Helper\Check;
use App\Console\Command;
use App\Helper\Apache;

class VhostBasicAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:basic-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a basic auth password protection to a domain';

    protected $check;
    protected $apache;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Check $check, Apache $apache)
    {
        parent::__construct();

        $this->check = $check;
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
        $user = $this->ask('User?');
        $password = $this->secret('Password?');
        $password_again = $this->secret('Password again?');

        VhostBasicAuthTaskManager::work([
            'domain'      => $domain,
            'user'         => $user,
            'password'    => $password,
            'password_again' => $password_again,
        ]);
    }
}
