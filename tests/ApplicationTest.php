<?php

namespace Tests;

use LaravelZero\Framework\Contracts\Exceptions\ConsoleException;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_has_environment_getter()
    {
        $this->assertSame('Test version', $this->app->version());
    }

    /** @test */
    public function it_confirms_the_running_in_console()
    {
        $this->assertTrue(
            $this->app->runningInConsole()
        );
    }

    /** @test */
    public function it_confirms_that_is_not_down_for_maintenance()
    {
        $this->assertFalse(
            $this->app->isDownForMaintenance()
        );
    }

    /** @test */
    public function it_can_abort(): void
    {
        try {
            $this->app->abort(404, 'Foo');
        } catch (CommandNotFoundException $notFoundException) {
        }

        $this->assertInstanceOf(CommandNotFoundException::class, $notFoundException);
        $this->assertEquals($notFoundException->getMessage(), 'Foo');

        try {
            abort(200, 'Bar', ['Foo' => 'Bar']);
        } catch (ConsoleException $consoleException) {
        }

        $this->assertInstanceOf(ConsoleException::class, $consoleException);
        $this->assertEquals($consoleException->getExitCode(), 200);
        $this->assertEquals($consoleException->getMessage(), 'Bar');
        $this->assertEquals($consoleException->getHeaders(), ['Foo' => 'Bar']);
    }
}
