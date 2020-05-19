<?php

declare(strict_types=1);

use LaravelZero\Framework\Contracts\Exceptions\ConsoleExceptionContract;
use Symfony\Component\Console\Exception\CommandNotFoundException;

it('retrieves test version from the config', function () {
    assertSame('Test version', $this->app->version());
});

it('is running in the console', function () {
    assertTrue($this->app->runningInConsole());
});

it('is not down for maintenance', function () {
    assertFalse($this->app->isDownForMaintenance());
});

it('can abort', function () {
    try {
        $this->app->abort(404, 'Foo');
    } catch (CommandNotFoundException $notFoundException) {
    }

    assertInstanceOf(CommandNotFoundException::class, $notFoundException);
    assertEquals($notFoundException->getMessage(), 'Foo');

    try {
        abort(200, 'Bar', ['Foo' => 'Bar']);
    } catch (ConsoleExceptionContract $consoleException) {
    }

    assertInstanceOf(ConsoleExceptionContract::class, $consoleException);
    assertEquals($consoleException->getExitCode(), 200);
    assertEquals($consoleException->getMessage(), 'Bar');
    assertEquals($consoleException->getHeaders(), ['Foo' => 'Bar']);
});
