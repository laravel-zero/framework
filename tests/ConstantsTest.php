<?php

declare(strict_types=1);

namespace Tests;

final class ConstantsTest extends TestCase
{
    /** @test */
    public function it_defines_the_artisan_binary()
    {
        $this->assertEquals(ARTISAN_BINARY, 'application');
    }
}
