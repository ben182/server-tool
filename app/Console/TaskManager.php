<?php

namespace App\Console;

use Exception;
use App\Helper\Config;
use App\Helper\Shell\Shell;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

abstract class TaskManager
{
    public $options;
    public $tasks          = [];
    public $customBindings = [];
    public $errorBag;
    public $command;

    /**
     * @var \App\Helper\Shell\Shell
     */
    public $shell;

    /**
     * @var \App\Console\TaskManager
     */
    public static $rootTaskManager;
    public static $conclusions = [];

    public function __construct($aOptions = [])
    {
        $this->options = (object) $aOptions;

        $this->shell   = app(Shell::class);
        $this->command = CommandHolder::getCommand();

        $this->errorBag = new Collection;

        $validator = Validator::make($aOptions, $this->validate());

        if ($validator->fails()) {
            $errors = collect($validator->messages())->flatten()->each(function ($message) {
                $this->command->error($message);
            });
            exit();
        }

        if (! self::$rootTaskManager) {
            self::$rootTaskManager = get_class($this);
        }
    }

    public function validate()
    {
        return [];
    }

    public static function work($options = [])
    {
        $obj = new static($options);
        $obj->_work();

        return $obj;
    }

    public static function addConclusion($aItems)
    {
        self::$conclusions[] = $aItems;
        self::$conclusions   = Arr::flatten(self::$conclusions);
    }

    public function printConclusions()
    {
        foreach (self::$conclusions as $conclusion) {
            $this->command->info($conclusion);
        }
    }

    public function addVariableBinding() : array
    {
        return [];
    }

    public function addBindings($aBindings)
    {
        $this->customBindings = array_merge($this->customBindings, $aBindings);
    }

    public function isRootManager()
    {
        return self::$rootTaskManager === get_class($this);
    }
    public function isNotRootManager()
    {
        return ! $this->isRootManager();
    }

    public function _work()
    {
        foreach ($this->tasks as $cTask) {
            $oTask = new $cTask($this->options, array_merge($this->addVariableBinding(), $this->customBindings), $this->errorBag);

            $mSystemRequirements = $oTask->systemRequirements();

            if (is_bool($mSystemRequirements) && $mSystemRequirements === false) {
                $this->command->error($oTask->systemRequirementsErrorMessage);
                continue;
            }
            if (is_string($mSystemRequirements)) {
                if (! app(Config::class)->isInstalled($mSystemRequirements)) {
                    $this->command->error(($oTask->systemRequirementsErrorMessage ?? $oTask->sName) . ' failed because ' . $mSystemRequirements . ' is not installed on your system.');
                    continue;
                }
            }
            if (! $oTask->localRequirements()) {
                continue;
            }

            if ($this->isRootManager()) {
                $this->command->line($oTask->name . '...started');
            }

            try {
                $oTask->handle();
            } catch (Exception $e) {
                $this->errorBag[$e];
            }

            if ($this->isRootManager()) {
                $method = $this->errorBag->isEmpty() ? 'info' : 'error';
                $this->command->$method($oTask->name . '...' . ($this->errorBag->isEmpty() ? 'done' : 'failed'));
            }

            if ($this->errorBag->isNotEmpty()) {
                $this->command->break();
                $this->command->error("{$this->errorBag->count()} " . Str::plural('error', $this->errorBag->count()) . ' found:');
                $this->command->line(implode("\n", $this->errorBag->toArray()));
                $this->errorBag = new Collection;
                break;
            }

            $this->addBindings($oTask->customBindings);

            $this->addConclusion($oTask->conclusions);
        }

        if ($this->isNotRootManager()) {
            return;
        }

        $this->command->break();
        $this->printConclusions();
    }
}
