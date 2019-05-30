<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\FloatingIpCreate\FloatingIpCreate;

class FloatingIpCreateTaskManager extends TaskManager
{
    public $tasks = [
        FloatingIpCreate::class,
    ];

    public function validate()
    {
        return [
            'ip' => 'required',
        ];
    }
}
