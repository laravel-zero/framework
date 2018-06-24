<?php

declare(strict_types=1);

namespace Tests;

use LaravelZero\Framework\Exceptions\ConsoleException;

final class ConsoleExceptionTest extends TestCase
{
    public function testExitCode(): void
    {
        $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

        $this->assertEquals(404, $exception->getExitCode());
    }

    public function testHeadersSetter(): void
    {
        $exception = new ConsoleException(404, 'Foo', ['bar' => 'etc']);

        $this->assertEquals(['bar' => 'etc'], $exception->getHeaders());

        $exception->setHeaders(['foo' => 'zero']);

        $this->assertEquals(['foo' => 'zero'], $exception->getHeaders());
    }
}
