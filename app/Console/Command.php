<?php

namespace App\Console;

use App\Helper\Shell\Shell;
use Illuminate\Console\Command as CoreCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

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

        // add the debug option to the default options
        $this->getDefinition()->addOption(new InputOption(
            'debug',
            'd',
            null,
            'Display all shell commands'
        ));
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

        if ($this->option('debug') === true) {
            $this->shell->setOutputEveryCommand();
        }

        parent::run($input, $output);
    }
}
