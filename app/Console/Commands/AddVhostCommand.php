<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\AddVhost;
use App\Console\ModCommand;
use Illuminate\Console\Command;

class AddVhostCommand extends ModCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:add
                            {dev? : Will not provision a real ssl certificate }';

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
        $bDev = $this->argument('dev');
        $sDomain = $this->ask('Domain?');
        $bWww = $this->confirm('www Alias?', true);
        $sHtaccess = $this->choice('htaccess?', [
            'Non SSL to SSL and www to non www',
            'Non SSL to SSL',
            'www to non www',
            'Nothing',
        ]);

        $bSsl = $this->confirm('SSL?', true);

        $sEmail = '';
        if ($bSsl) {
            $sEmail = $this->ask('SSL Email?');
        }

        (new AddVhost([
            'dev'         => $bDev,
            'domain'      => $sDomain,
            'www'         => $bWww,
            'htaccess'    => $sHtaccess,
            'ssl'         => $bSsl,
            'ssl_email'   => $sEmail,
        ]))->work();
    }
}
