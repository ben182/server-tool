<?php

namespace App\Console\Commands;

use App\Console\ModCommand;
use App\Repository;
use Illuminate\Console\Command;

class GitAutoDeployListCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gad:list';

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
        $cRepositories = Repository::all();

        foreach ($cRepositories as $oRepository) {
            $this->line($oRepository->id . ':');

            $this->line(' - Url: ' . route('api.gad.deploy', [
                'repository' => $oRepository->slug()
            ]));
            $this->line(' - Secret: ' . $oRepository->secret);
            $this->line(' - Dir: ' . $oRepository->full_dir);
            $this->line('');
        }
    }
}
