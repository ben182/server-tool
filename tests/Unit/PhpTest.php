<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhpTest extends TestCase
{
    public function testThatServiceIsRunning() {
        $this->testThatServiceIsRunning('php7.2-fpm');
    }

    public function testComposer() {
        $this->assertThatCommandOutputContains('Composer version', 'composer -V');
    }

    public function testModules() {
        $this->assertThatCommandOutputContains([
            'curl',
            'mbstring',
            'PDO',
        ], 'php -m');
    }
}
