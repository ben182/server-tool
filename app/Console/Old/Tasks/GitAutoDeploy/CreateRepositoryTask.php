<?php

namespace App\Console\Commands\Tasks\GitAutoDeploy;

use App\Repository;
use App\Console\Commands\Tasks\Task;

class CreateRepositoryTask extends Task
{
    public $name = 'Creating Repository';

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
            'dir'    => $this->options->dir,
            'branch' => $this->options->branch,
        ]);

        copy(templates_path('git/deploy_stool.sh'), $this->options->dir . '/deploy_stool.sh');

        $this->addConclusion($this->options->dir . '/deploy_stool.sh');
        $this->addConclusion('Add this route to a new github repo webhook');
        $this->addConclusion(action('RepositoryController@index', $oRepository));
        $this->addConclusion('Put this as a secret');
        $this->addConclusion($oRepository->secret);
    }
}
