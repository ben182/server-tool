<?php

namespace App\Helper\Shell;

class Shell
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

    public function getLastOutput()
    {
        return $this->lastExecOutput;
    }

    public function outputEveryCommand(bool $bData = true)
    {
        $this->bDebug = $bData;
        return $this;
    }

    public function echo($sData)
    {
        $this->aOutputs[] = $sData;
        return $this;
    }

    public function saveOutput()
    {
        $this->aOutputs[] = $this->lastExecOutput;
        return $this;
    }

    public function getOutput()
    {
        return implode("\n", $this->aOutputs) . "\n";
    }

    public function flushOutput()
    {
        $this->aOutputs = [];
        return $this;
    }

    public function bash($sName)
    {
        $this->exec('bash ' . $sName);
        return $this;
    }

    public function execScript($sName)
    {
        $this->exec('bash ' . scripts_path() . $sName . '.sh');
        return $this;
    }

    public function execScriptAsStool($sName)
    {
        $this->exec('sudo -H -u stool bash ' . scripts_path() . $sName . '.sh');
        return $this;
    }

    public function copy($sFrom, $sTo) {
        $this->exec("sudo cp $sFrom $sTo");
        return $this;
    }

    public function removeFile($sFile) {
        $this->exec("sudo rm $sFile");
        return $this;
    }

    public function removeFolder($sFile) {
        $this->exec("sudo rm -r $sFile");
        return $this;
    }

    public function replaceStringInFile($sNeedle, $sReplace, $sFile) {
        $this->exec('sudo sed -i "s|' . $sNeedle . '|' . $sReplace . '|g" ' . $sFile);
        return $this;
    }

    public function getFile($sFile) {
        $this->exec('sudo cat ' . $sFile);
        return $this;
    }

    public function saveError($sError)
    {
        $this->aErrors[] = $sError;
        return $this;
    }

    public function getErrors()
    {
        return implode("\n", $this->aErrors);
    }

    public function hasErrors()
    {
        return $this->countErrors() > 0;
    }

    public function countErrors()
    {
        return count($this->aErrors);
    }

    public function flushErrors()
    {
        $this->aErrors = [];
        return $this;
    }

    public function cronjob()
    {
        return app(Cronjob::class);
    }

    public function environment()
    {
        return app(Environment::class);
    }

    public function mysql()
    {
        return app(Mysql::class);
    }
}
