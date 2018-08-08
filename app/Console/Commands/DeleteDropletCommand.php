<?php

namespace App\Console\Commands;

use GrahamCampbell\DigitalOcean\DigitalOceanManager;
use Illuminate\Console\Command;

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
        $cDropletIds = collect();
        $cDroplets->each(function ($item) use (&$cDropletIds) {
            if ($item->name === 'stool-test') {
                $cDropletIds->push($item->id);
                return;
            }
        });

        if ($cDropletIds->count() === 0) {
            echo 'Droplet could not be found';
            return;
        }

        $cDropletIds->each(function($item) {
            $this->digitalocean->droplet()->delete($item);
            echo 'Droplet deleted';
        });
    }
}
