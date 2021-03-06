<?php

namespace App\Helper\Shell;

use Illuminate\Support\Str;

class Shell
{
    protected $lastOutput;
    protected $quiet              = false;
    protected $quietTemp          = false;
    protected $outputEveryCommand = false;

    public function exec($sCommand)
    {
        if ($this->outputEveryCommand) {
            echo $sCommand . " 2>&1\n";
        }

        $this->lastOutput = $this->liveExecuteCommand($sCommand);

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

    public function setOutputEveryCommand(bool $bool = true)
    {
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
        $sNeedle = str_replace('"', '\"', $sNeedle);
        // $sNeedle = preg_quote($sNeedle, '"');

        $sReplace = str_replace('\"', '"', $sReplace);
        $sReplace = str_replace('"', '\"', $sReplace);
        // $sReplace = preg_quote($sReplace, '"');

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

    public function doesFileExist($file)
    {
        $this->setQuitForNextCommand();

        $this->exec('[ -f "' . $file . '" ] && echo "exists"');

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

    /**
     * Execute the given command by displaying console output live to the user.
     *
     *  @param  string  cmd          :  command to be executed
     *
     *  @return array   exit_status  :  exit status of the executed command
     *                  output       :  console output of the executed command
     */
    protected function liveExecuteCommand($cmd)
    {
        while (@ ob_end_flush()); // end all output buffers if any

        $proc = popen("$cmd 2>&1", 'r');

        $live_output     = "";
        $complete_output = "";

        while (! feof($proc)) {
            $live_output     = fread($proc, 4096);
            $complete_output = $complete_output . $live_output;

            if (! $this->quiet && ! $this->quietTemp) {
                echo $live_output;
            }

            @ flush();
        }

        pclose($proc);

        return $complete_output;
    }
}
