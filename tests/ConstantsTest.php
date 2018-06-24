<?php

declare(strict_types=1);

namespace Tests;

final class ConstantsTest extends TestCase
{
    public function testArtisanBinary(): void
    {
        $this->assertEquals(ARTISAN_BINARY, 'application');
    }
}
