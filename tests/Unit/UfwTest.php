<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UfwTest extends TestCase
{
    public function testThatServiceIsRunning() {
        $this->assertServiceIsRunning('ufw');
    }

    public function testThatRulesAreEnabled() {
        $this->assertThatCommandOutputContains(['12920/tcp', 'Apache Full'], 'ufw status');
    }
}
