<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupervisorTest extends TestCase
{
    public function testThatServiceIsRunning() {
        $this->assertServiceIsRunning('supervisor');
    }
}
