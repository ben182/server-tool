<?php

namespace App\Helper\Shell;

class Shell
{
    protected $lastOutput;
    protected $quiet     = false;
    protected $quietTemp = false;

    public function exec($sCommand)
    {
        $this->lastOutput = shell_exec($sCommand . ' 2>&1');

        if (! $this->quiet && ! $this->quietTemp) {
            echo $this->lastOutput;
        }

        if ($this->quietTemp) {
            $this->quietTemp = false;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getLastOutput()
    {
        return $this->lastOutput;
    }

    public function setQuiet(bool $bool = true)
    {
        $this->quiet = $bool;

        return $this;
    }

    public function setQuitForNextCommand(bool $bool = true)
    {
        $this->quietTemp = $bool;

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

    public function copy($sFrom, $sTo)
    {
        $this->exec("sudo cp $sFrom $sTo");

        return $this;
    }

    public function removeFile($sFile)
    {
        $this->exec("sudo rm $sFile");

        return $this;
    }

    public function removeFolder($sFile)
    {
        $this->exec("sudo rm -r $sFile");

        return $this;
    }

    public function replaceStringInFile($sNeedle, $sReplace, $sFile)
    {
        $this->exec('sudo sed -i "s|' . $sNeedle . '|' . $sReplace . '|g" ' . $sFile);

        return $this;
    }

    public function getFile($sFile)
    {
        $this->exec('sudo cat ' . $sFile);

        return $this->getLastOutput();
    }

    /**
     * @return \App\Helper\Shell\Cronjob
     */
    public function cronjob()
    {
        return app(Cronjob::class);
    }

    /**
     * @return \App\Helper\Shell\Environment
     */
    public function environment()
    {
        return app(Environment::class);
    }

    /**
     * @return \App\Helper\Shell\Mysql
     */
    public function mysql()
    {
        return app(Mysql::class);
    }

    /**
     * @return \App\Helper\Shell\Service
     */
    public function service()
    {
        return app(Service::class);
    }
}
