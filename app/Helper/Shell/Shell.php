<?php

namespace App\Helper\Shell;

use Illuminate\Support\Str;

class Shell
{
    protected $lastOutput;
    protected $quiet     = false;
    protected $quietTemp = false;
    protected $outputEveryCommand = false;

    public function exec($sCommand)
    {
        if ($this->outputEveryCommand) {
            echo $sCommand . " 2>&1\n";
        }

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

    public function setOutputEveryCommand(bool $bool = true) {
        $this->outputEveryCommand = $bool;

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

    public function copy($sFrom, $sTo, $asStool = false)
    {
        if ($asStool) {
            $this->exec("cp $sFrom $sTo");
        } else {
            $this->exec("sudo cp $sFrom $sTo");
        }

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
        // escaping
        $sNeedle = str_replace('\"', '"', $sNeedle);
        $sNeedle = preg_quote($sNeedle, '"');

        $sReplace = str_replace('\"', '"', $sReplace);
        $sReplace = preg_quote($sReplace, '"');

        $this->exec('sudo sed -i "s|' . $sNeedle . '|' . $sReplace . '|g" ' . $sFile);

        return $this;
    }

    public function getFile($sFile)
    {
        $this->setQuitForNextCommand();

        $this->exec('sudo cat ' . $sFile);

        return $this->getLastOutput();
    }

    public function isStringInFile($file, $needle)
    {
        $this->setQuitForNextCommand();

        $this->exec("cat $file | grep '$needle'");

        return Str::contains($this->getLastOutput(), $needle);
    }

    public function doesFolderExist($folder)
    {
        $this->setQuitForNextCommand();

        $this->exec('[ -d "' . $folder . '" ] && echo "exists"');

        return Str::contains($this->getLastOutput(), 'exists');
    }

    /**
     * @return \App\Helper\Shell\Cronjob
     */
    public function cronjob()
    {
        return app('stool-shell-cronjob');
    }

    /**
     * @return \App\Helper\Shell\Environment
     */
    public function environment()
    {
        return app('stool-shell-environment');
    }

    /**
     * @return \App\Helper\Shell\Mysql
     */
    public function mysql()
    {
        return app('stool-shell-mysql');
    }

    /**
     * @return \App\Helper\Shell\Service
     */
    public function service()
    {
        return app('stool-shell-service');
    }
}
