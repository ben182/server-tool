<?php

namespace App\Console\Commands\Installation;

use App\Setting;
use App\Helper\Hardware;
use App\Helper\Shell\Shell;
use Illuminate\Console\Command;

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

    protected $config;
    protected $shell;
    protected $hardware;

    protected $aToInstall = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Shell $shell, Hardware $hardware)
    {
        parent::__construct();

        $this->shell    = $shell;
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
            $recommendedSize = (int) round($this->hardware->getTotalRam());

            $iSwap = (int) $this->ask('How much in GB (Recommended is >= than ' . $recommendedSize . 'GB) ?', $recommendedSize);
        }

        Setting::createKey('admin_email', $sEmail);

        $this->shell->setQuitForNextCommand();
        $this->shell->bash(scripts_path('partials/swap.sh') . ' ' . $iSwap . 'G');
    }
}
