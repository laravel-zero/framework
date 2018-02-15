<?php

namespace Tests;

class ConstantsTest extends TestCase
{
    /** @test */
    public function it_defines_the_artisan_binary()
    {
        $this->assertEquals(ARTISAN_BINARY, 'application');
    }
}
