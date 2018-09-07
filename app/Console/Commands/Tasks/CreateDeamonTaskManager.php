<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\CreateDeamon\CreateDeamonTask;

class CreateDeamonTaskManager extends Taskmanager
{
    public $aTasks = [
        CreateDeamonTask::class,
    ];

    public function validate()
    {
        return [
            'name'    => 'required',
            'command' => 'required',
        ];
    }
}
