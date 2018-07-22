<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webmozart\Assert\Assert;

class InstallationTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installation:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->testThatDropletIdIsSetCorrectly();
        $this->testThatConfigJsonIsGenerated();
        $this->testThatApacheWorks();
        $this->testThatMySqlWorks();
    }

    private function testThatDropletIdIsSetCorrectly()
    {
        $this->task('Testing Variables', function () {
            return $this->assert('notSame', getenv('DROPLET_ID'), false);
        });
    }

    private function testThatConfigJsonIsGenerated()
    {
        $this->task('Testing config.json', function () {
            return $this->assert('fileExists', base_path('config.json'));
        });
    }

    private function testThatApacheWorks()
    {
        $this->task('Testing Apache Status', function () {
            return $this->isServiceActive('apache2');
        });

        $this->task('Testing Apache Conf Syntax', function () {
            return $this->assert('contains', shell_exec('apachectl configtest 2>&1'), 'Syntax OK');
        });

        $this->task('Testing Apache Security', function () {
            $aAssert[] = $this->assert('contains', file_get_contents('/etc/apache2/apache2.conf'), 'Header always append X-Frame-Options SAMEORIGIN');
            $aAssert[] = $this->assert('contains', file_get_contents('/etc/apache2/apache2.conf'), 'ServerTokens Prod');
            $aAssert[] = $this->assert('contains', file_get_contents('/etc/apache2/apache2.conf'), 'Timeout 60');
            return array_product($aAssert) === 1;
        });
    }

    private function testThatMySqlWorks()
    {
        $this->task('Testing MySQL Status', function () {
            return $this->isServiceActive('mysql');
        });

        $this->task('Testing MySQL Queries', function () {
            $aAssert[] = $this->assert('contains', buildMysqlCommand('SHOW DATABASES', true), 'Database');
            $aAssert[] = $this->assert('contains', buildMysqlCommand('SHOW DATABASES', true), 'information_schema');
            $aAssert[] = $this->assert('contains', buildMysqlCommand('SHOW DATABASES', true), 'servertools');
            return array_product($aAssert) === 1;
        });

        $this->task('Testing MySQL Security', function () {
            $aAssert[] = $this->assert('notContains', buildMysqlCommand("SELECT * FROM mysql.user WHERE User=''", true), 'Host');
            $aAssert[] = $this->assert('notContains', buildMysqlCommand("SELECT * FROM mysql.user WHERE User=''", true), 'User');
            $aAssert[] = $this->assert('notContains', buildMysqlCommand("SELECT * FROM mysql.user WHERE User=''", true), 'localhost');
            return array_product($aAssert) === 1;
        });

        $this->task('Testing MySQL config.json', function () {
            return $this->assert('notContains', file_get_contents(base_path('config.json')), 'ROOT_PASSWORD_HERE');
        });
    }

    private function assert($sMethod, ...$aFunctionArguments)
    {
        try {
            Assert::$sMethod(...$aFunctionArguments);
            return true;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    private function isServiceActive($sServiceName)
    {
        return $this->assert('contains', shell_exec('systemctl is-enabled ' . $sServiceName), 'enabled');
    }
}
