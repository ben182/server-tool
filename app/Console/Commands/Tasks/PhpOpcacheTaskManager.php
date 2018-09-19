<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\PhpOpcache\PhpOpcacheTask;


class PhpOpcacheTaskManager extends Taskmanager
{
    public $aTasks = [
        PhpOpcacheTask::class,
    ];

    public function validate()
    {
        return [
            'mode' => 'required|boolean',
        ];
    }
}
