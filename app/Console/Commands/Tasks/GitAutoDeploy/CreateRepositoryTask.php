<?php

namespace App\Console\Commands\Tasks\GitAutoDeploy;

use App\Console\Commands\Tasks\Task;
use App\Repository;

class CreateRepositoryTask extends Task
{
    public $sName = 'Creating Repository';

    public function systemRequirements()
    {
        return true;
    }

    public function localRequirements()
    {
        return true;
    }

    public function handle()
    {
        $oRepository = Repository::create([
            'dir'    => $this->oOptions->dir,
        ]);

        copy(templates_path('git/deploy_stool.sh'), $this->oOptions->dir . '/deploy_stool.sh');

        $this->addConclusion($this->oOptions->dir . '/deploy_stool.sh');
        $this->addConclusion('Add this route to a new github repo webhook');
        $this->addConclusion(action('RepositoryController@index', $oRepository));
        $this->addConclusion('Put this as a secret');
        $this->addConclusion($oRepository->secret);
    }
}