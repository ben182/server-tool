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
        $this->assertFileContains([
            'Header always append X-Frame-Options SAMEORIGIN',
            'ServerTokens Prod',
            'Timeout 60',
            'Protocols h2 h2c http/1.1',
        ], '/etc/apache2/apache2.conf');
    }

    public function testModules() {
        $this->assertThatCommandOutputContains([
            'proxy_http',
            'rewrite',
            'expires',
            'http2',
            'mpm_event',
            'proxy_fcgi',
            'setenvif',
        ], 'apache2ctl -M');

        $this->assertThatCommandOutputContainsNot([
            'mpm_prefork',
        ], 'apache2ctl -M');
    }

    public function testThatIpSiteIsEnabled() {
        $this->assertFileExists('/etc/apache2/sites-enabled/ip.conf');
        $this->assertFileContainsNot('IP_HERE', '/etc/apache2/sites-enabled/ip.conf');
    }
}
