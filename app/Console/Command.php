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

        $this->shell = app('stool-shell');

        // add the debug option to the default options
        $this->getDefinition()->addOption(new \Symfony\Component\Console\Input\InputOption(
            'debug',
            'd',
            null,
            'Display all shell commands'
        ));
    }

    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        CommandHolder::setCommand($this);
        if ($this->option('debug') === true) {
            $this->shell->setOutputEveryCommand();
        }

        return parent::execute($input, $output);
    }
}
