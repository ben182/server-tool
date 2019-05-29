<?php

namespace App\Console\Commands\AddVhost;

use App\Console\Command;

class AddVhostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vhost:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds a vHost';

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
        $sDomain = $this->ask('Domain?');

        $bRedirect   = $this->confirm('Redirect?', false);
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
            $sHtaccess = $this->choice('Htaccess?', $aRedirectChoices);
        }

        AddVhostTaskManager::work([
            'domain'      => $sDomain,
            'www'         => $bWww,
            'htaccess'    => $sHtaccess,
            'ssl'         => $bSsl,
            'redirect'    => $bRedirect,
            'redirect_to' => $bRedirectTo,
        ]);
    }
}
