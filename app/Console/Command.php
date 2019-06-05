<?php

namespace App\Console;

use App\Helper\Shell\Shell;
use Illuminate\Console\Command as CoreCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends CoreCommand
{
    /**
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

    /**
     * Run the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     *
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        CommandHolder::setCommand($this);

        parent::run($input, $output);
    }
}
