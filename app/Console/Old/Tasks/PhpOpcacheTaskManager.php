<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\PhpOpcache\PhpOpcacheTask;

class PhpOpcacheTaskManager extends TaskManager
{
    public $tasks = [
        PhpOpcacheTask::class,
    ];

    public function validate()
    {
        return [
            'mode' => 'required|boolean',
        ];
    }
}
