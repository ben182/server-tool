<?php

namespace App\Console\Commands;

use GrahamCampbell\DigitalOcean\DigitalOceanManager;
use Illuminate\Console\Command;

class CreateDropletCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $digitalocean;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DigitalOceanManager $digitalocean)
    {
        parent::__construct();
        $this->digitalocean = $digitalocean;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $oDroplet = $this->digitalocean->droplet()->create('stool-test', 'fra1', 's-1vcpu-1gb', 'ubuntu-18-04-x64', false, false, true, [17777590]);

        sleep(60);
        $sIp = $this->digitalocean->droplet()->getById($oDroplet->id)->networks[0]->ipAddress;

        $this->line('IP: ' . $sIp);

        $sBranch = implode('/', array_slice(explode('/', file_get_contents('.git/HEAD')), 2));
        echo str_replace('develop', $sBranch, file_get_contents(scripts_path('get_develop.sh')));

        shell_exec('"C:\Program Files\PuTTY\putty.exe" root@' . $sIp);
    }
}
