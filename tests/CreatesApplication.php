<?php

namespace Tests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    protected $shell;
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        Hash::driver('bcrypt')->setRounds(4);

        $this->shell = app('ShellTask');

        return $app;
    }

    protected function assertServiceIsRunning($sServiceName) {
        $this->assertContains('enabled', $this->shell->exec('systemctl is-enabled ' . $sServiceName)->getLastOutput(), 'The service ' . $sServiceName . ' is not running. Fix it by typing "service ' . $sServiceName . ' start"');
    }

    protected function assertThatCommandOutputContains($mNeedle, $sCommand) {
        $this->commandOutputHelper($mNeedle, $this->shell->exec($sCommand)->getLastOutput(), $sCommand);
    }
    protected function assertThatMysqlCommandOutputContains($mNeedle, $sCommand) {
        $this->commandOutputHelper($mNeedle, $this->shell->mysql()->execCommand($sCommand)->getLastOutput(), $sCommand);
    }
    protected function assertThatCommandOutputContainsNot($mNeedle, $sCommand) {
        $this->commandOutputHelper($mNeedle, $this->shell->exec($sCommand)->getLastOutput(), $sCommand, true);
    }
    protected function assertThatMysqlCommandOutputContainsNot($mNeedle, $sCommand) {
        $this->commandOutputHelper($mNeedle, $this->shell->mysql()->execCommand($sCommand)->getLastOutput(), $sCommand, true);
    }

    protected function commandOutputHelper($mNeedle, $sOutput, $sCommand, $bNot = false) {
        foreach (array_wrap($mNeedle) as $sNeedle) {
            if ($bNot) {
                $this->assertNotContains($sNeedle, $sOutput, 'The command ' . $sCommand . ' output did contain ' . $sNeedle);
                continue;
            }
            $this->assertContains($sNeedle, $sOutput, 'The command ' . $sCommand . ' output did not contain ' . $sNeedle);
        }
    }

    protected function assertFileContains($mNeedle, $sFile) {
        $sOutput = $this->shell->getFile($sFile)->getLastOutput();
        foreach (array_wrap($mNeedle) as $sNeedle) {
            $this->assertContains($sNeedle, $sOutput, 'The file ' . $sFile . ' output did not contain ' . $sNeedle);
        }
    }

    protected function assertFileContainsNot($mNeedle, $sFile) {
        $sOutput = $this->shell->getFile($sFile)->getLastOutput();
        foreach (array_wrap($mNeedle) as $sNeedle) {
            $this->assertNotContains($sNeedle, $sOutput, 'The file ' . $sFile . ' output did not contain ' . $sNeedle);
        }
    }
}
