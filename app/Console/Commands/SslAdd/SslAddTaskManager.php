<?php

namespace App\Console\Commands\SslAdd;

use App\Console\Command;
use App\Console\Commands\AddVhost\AddVhostTaskManager;
use App\Console\TaskManager;
use App\Console\Commands\AddVhost\Tasks\CreateSslCertificateTask;

class SslAddTaskManager extends TaskManager
{
    public $tasks = [
        CreateSslCertificateTask::class,
    ];

    public function addVariableBinding() : array
    {
        // CreateSslCertificateTask checks if this is true so we spoof it
        $this->options->ssl = true;

        return [];
    }
}
