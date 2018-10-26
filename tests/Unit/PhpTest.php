<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhpTest extends TestCase
{
    public function testComposer() {
        $this->assertThatCommandOutputContains('Composer version', 'composer -V');
    }
}
