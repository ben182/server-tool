<?php

namespace App\Console\Commands\Tasks\Shell;

class ShellTask
{
    private $lastExecOutput;
    private $aOutputs = [];
    private $aErrors = [];
    private $bDebug = false;

    public function exec($sCommand)
    {
        $this->lastExecOutput = shell_exec($sCommand . ' 2>&1');

        if ($this->bDebug) {
            $this->saveOutput();
        }

        return $this;
    }

    public function outputEveryCommand(bool $bData = true) {
        $this->bDebug = $bData;
        return $this;
    }

    public function echo($sData) {
        $this->aOutputs[] = $sData;
        return $this;
    }

    public function saveOutput() {
        $this->aOutputs[] = $this->lastExecOutput;
        return $this;
    }

    public function getOutput() {
        return implode("\n", $this->aOutputs) . "\n";
    }

    public function flushOutput() {
        $this->aOutputs = [];
        return $this;
    }

    public function execScript($sName)
    {
        $this->exec('bash ' . scripts_path() . $sName . '.sh');
        return $this;
    }

    public function saveError($sError) {
        $this->aErrors[] = $sError;
        return $this;
    }

    public function getErrors() {
        return implode("\n", $this->aErrors);
    }

    public function hasErrors() {
        return $this->countErrors() > 0;
    }

    public function countErrors() {
        return count($this->aErrors);
    }

    public function flushErrors() {
        $this->aErrors = [];
        return $this;
    }

    public function cronjob() {
        return new Cronjob;
    }

    public function environment() {
        return new Environment;
    }
}
