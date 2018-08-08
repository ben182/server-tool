<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GrahamCampbell\DigitalOcean\DigitalOceanManager;

class DeleteDropletCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:delete';

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
        $cDroplets = collect($this->digitalocean->droplet()->getAll());
        $iDroplet = null;
        $cDroplets->each(function ($item, $key) use (&$iDroplet) {

            if ($item->name === 'stool-test') {
                $iDroplet = $item->id;
                return;
            }
        });

        if (!$iDroplet) {
            echo 'Droplet could not be found';
            return;
        }

        $this->digitalocean->droplet()->delete($iDroplet);
        echo 'Droplet deleted';
    }
}
