<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Config;
use App\Helper\Shell\Shell;
use App\Setting;

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

    protected $aToInstall = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Shell $shell)
    {
        parent::__construct();

        $this->shell = $shell;
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
            $iSwap = (int) $this->ask('How much (in GB)?');
        }

        Setting::createKey('admin_email', $sEmail);

        $this->shell->bash(scripts_path('partials/swap.sh') . ' ' . $iSwap . 'G');
    }
}
