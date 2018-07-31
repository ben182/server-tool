<?php

namespace App\Console\Commands\Tasks;

use App\Console\Commands\Tasks\AddVhost\ConfigureApacheConfiguration;
use App\Console\Commands\Tasks\AddVhost\ConfigureRedirects;
use App\Console\Commands\Tasks\AddVhost\CreateApacheConfiguration;
use App\Console\Commands\Tasks\AddVhost\CreateSslCertificate;
use App\Console\Commands\Tasks\Partials\Finish;
use Illuminate\Validation\Rule;
use App\Console\Commands\Tasks\TestTest\TestTask;
use App\Console\Commands\Tasks\TestTest\TestTask2;

class TestTestTaskManager extends Taskmanager
{
    public $aTasks = [
        TestTask::class,
        TestTask2::class,
    ];

    public function validate()
    {
        return [];
    }
}
