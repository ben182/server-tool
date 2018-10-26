<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApacheTest extends TestCase
{
    public function testThatServiceIsRunning() {
        $this->assertServiceIsRunning('apache2');
    }

    public function testThatConfigSytaxIsOk() {
        $this->assertThatCommandOutputContains('Syntax OK', 'apachectl configtest');
    }

    public function testSecurity() {
        $this->assertFileContains(['Header always append X-Frame-Options SAMEORIGIN', 'ServerTokens Prod', 'Timeout 60'], '/etc/apache2/apache2.conf');
    }
}
