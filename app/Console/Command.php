<?php

namespace App\Console;

use App\Helper\Shell\Shell;
use Illuminate\Console\Command as CoreCommand;

class Command extends CoreCommand
{
    /**
     * Shell
     *
     * @var \App\Helper\Shell\Shell
     */
    protected $shell;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->shell = app(Shell::class);
    }
}
