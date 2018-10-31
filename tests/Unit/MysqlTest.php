<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MysqlTest extends TestCase
{
    public function testThatServiceIsRunning() {
        $this->assertServiceIsRunning('mysql');
    }

    public function testQueries() {
        $this->assertThatMysqlCommandOutputContains([
            'Database',
            'information_schema',
            'stool',
        ], 'SHOW DATABASES');
    }

    public function testSecurity() {
        $this->assertThatMysqlCommandOutputContainsNot([
            'Host',
            'User',
            'localhost',
        ], "SELECT * FROM mysql.user WHERE User=''");
    }
}
