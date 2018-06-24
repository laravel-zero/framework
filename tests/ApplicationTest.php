<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Component\Console\Exception\CommandNotFoundException;
use LaravelZero\Framework\Contracts\Exceptions\ConsoleExceptionContract;

final class ApplicationTest extends TestCase
{
    public function testVersionFromConfig(): void
    {
        $this->assertSame('Test version', $this->app->version());
    }

    public function testRunningInConsole(): void
    {
        $this->assertTrue($this->app->runningInConsole());
    }

    public function testIsDownForMaintenance(): void
    {
        $this->assertFalse($this->app->isDownForMaintenance());
    }

    public function testThatCanAbort(): void
    {
        try {
            $this->app->abort(404, 'Foo');
        } catch (CommandNotFoundException $notFoundException) {
        }

        $this->assertInstanceOf(CommandNotFoundException::class, $notFoundException);
        $this->assertEquals($notFoundException->getMessage(), 'Foo');

        try {
            abort(200, 'Bar', ['Foo' => 'Bar']);
        } catch (ConsoleExceptionContract $consoleException) {
        }

        $this->assertInstanceOf(ConsoleExceptionContract::class, $consoleException);
        $this->assertEquals($consoleException->getExitCode(), 200);
        $this->assertEquals($consoleException->getMessage(), 'Bar');
        $this->assertEquals($consoleException->getHeaders(), ['Foo' => 'Bar']);
    }
}
