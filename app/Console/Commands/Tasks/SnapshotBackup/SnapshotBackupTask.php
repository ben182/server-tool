<?php

namespace App\Console\Commands\Tasks\SnapshotBackup;

use App\Console\Commands\Tasks\Task;

class SnapshotBackupTask extends Task
{
    public $sName = 'Backing up Droplet';
    public $systemRequirementsErrorMessage = 'Your droplet id or your digitalocean access token is not set';

    public function systemRequirements()
    {
        return getenv('DROPLET_ID') != false && getenv('DOAT') != false;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $iDropletId = getenv('DROPLET_ID');

        $sToken = decrypt(getenv('DOAT'));

        $this->shell->exec("/usr/local/bin/do_snapshot --only $iDropletId -k {$this->oOptions->keep} -c -q --digital-ocean-access-token $sToken 2>&1");
    }
}
