<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\CreateDeamon\CreateDeamonTask;

class CreateDeamonTaskManager extends TaskManager
{
    public $tasks = [
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
