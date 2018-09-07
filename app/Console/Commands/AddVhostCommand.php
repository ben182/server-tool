<?php

namespace App\Console\Commands;

use App\Console\Commands\Tasks\AddVhostTaskManager;
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
        parent::handle();

        $bDev = $this->argument('dev') ?? false;
        $sDomain = $this->ask('Domain?');

        $bRedirect = $this->confirm('Redirect?', false);
        $bRedirectTo = '';
        if ($bRedirect) {
            $bRedirectTo = $this->ask('To?');
        }

        if (isSubdomain($sDomain)) {
            $bWww = false;
        } else {
            $bWww = $this->confirm('www Alias?', true);
        }
        

        $bSsl = $this->confirm('SSL?', true);

        $sEmail = '';
        if ($bSsl) {
            $sEmail = $this->ask('SSL Email?');
        }

        $sHtaccess = '';
        if (! $bRedirect) {
            $aRedirectChoices = [
                'Nothing',
            ];
    
            if ($bWww) {
                $aRedirectChoices[] = 'www to non www';
    
                if ($bSsl) {
                    $aRedirectChoices[] = 'Non SSL to SSL and www to non www';
                }
            }
            if ($bSsl) {
                $aRedirectChoices[] = 'Non SSL to SSL';
            }
            $sHtaccess = $this->choice('Redirect?', $aRedirectChoices);
        }

        (new AddVhostTaskManager([
            'dev'         => $bDev,
            'domain'      => $sDomain,
            'www'         => $bWww,
            'htaccess'    => $sHtaccess,
            'ssl'         => $bSsl,
            'ssl_email'   => $sEmail,
            'redirect'    => $bRedirect,
            'redirect_to' => $bRedirectTo,
        ]))->work();
    }
}
