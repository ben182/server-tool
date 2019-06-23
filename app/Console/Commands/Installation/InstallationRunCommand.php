<?php

namespace App\Console\Commands\Installation;

use App\Setting;
use App\Console\Command;
use App\Helper\Hardware;

class InstallationRunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $hardware;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Hardware $hardware)
    {
        parent::__construct();

        $this->hardware = $hardware;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Admin Email
        $sEmail = $this->ask('Administrator email?');

        // Swap
        $bAddSwap = $this->confirm('Add Swap Space?', true);
        if ($bAddSwap) {
            $recommendedSize = $this->hardware->getSwapSizeRecommendation();

            $iSwap = (int) $this->ask('How much in GB (Recommended is >= ' . $recommendedSize . 'GB) ?', $recommendedSize);
        }

        Setting::createKey('admin_email', $sEmail);

        $this->shell->setQuitForNextCommand();
        $this->shell->bash(scripts_path('partials/swap.sh') . ' ' . $iSwap . 'G');
    }
}
