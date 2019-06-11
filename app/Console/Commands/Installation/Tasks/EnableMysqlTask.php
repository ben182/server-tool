<?php

namespace App\Console\Commands\Installation\Tasks;

use App\Console\Task;

class EnableMysqlTask extends Task
{
    public $name = 'Enable MySQL';

    public function handle()
    {
        $mysqlPass = app('stool-password')->generate();
        $this->shell->mysql()->execCommand('GRANT USAGE ON *.* TO netdata@localhost IDENTIFIED BY \'' . $mysqlPass . '\';');
        $this->shell->mysql()->execCommand('FLUSH PRIVILEGES;');

        $this->shell->copy(templates_path('netdata/python.d/mysql.conf'), '/etc/netdata/python.d/mysql.conf');
        $this->shell->replaceStringInFile('<password>', $mysqlPass, '/etc/netdata/python.d/mysql.conf');

        $this->shell->service()->restart('netdata');
    }
}
