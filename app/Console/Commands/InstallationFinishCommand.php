<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Setting;
use App\Helper\Shell\Shell;

class InstallationFinishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $shell;

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
        Setting::create([
            'key'   => 'admin_email',
            'value' => $sEmail,
        ]);

        // Swap
        $bAddSwap = $this->confirm('Add Swap Space?');
        if ($bAddSwap) {
            $iSwap = (int) $this->ask('How much (in GB)?');

            $this->shell->bash(scripts_path('partials/swap.sh') . ' ' . $iSwap . 'G');
        }
    }
}
