<?php

declare(strict_types=1);

namespace Tests;

use LaravelZero\Framework\Exceptions\ConsoleException;

final class ConsoleExceptionTest extends TestCase
{
    /** @test */
    public function it_respects_the_exit_code(): void
    {
        $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

        $this->assertEquals(404, $exception->getExitCode());
    }

    /** @test */
    public function it_respects_the_headers(): void
    {
        $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

        $this->assertEquals(['bar' => 'etc'], $exception->getHeaders());
    }
}
