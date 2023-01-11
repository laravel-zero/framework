<?php

declare(strict_types=1);

use LaravelZero\Framework\Contracts\Exceptions\ConsoleExceptionContract;
use Symfony\Component\Console\Exception\CommandNotFoundException;

it('retrieves test version from the config', function () {
    expect($this->app->version())->toBe('Test version');
});

it('is running in the console', function () {
    expect($this->app->runningInConsole())->toBeTrue();
});

it('is not down for maintenance', function () {
    expect($this->app->isDownForMaintenance())->toBeFalse();
});

it('can abort', function () {
    try {
        $this->app->abort(404, 'Foo');
    } catch (CommandNotFoundException $notFoundException) {
    }

    expect($notFoundException)->toBeInstanceOf(CommandNotFoundException::class)
        ->and($notFoundException->getMessage())->toEqual('Foo');

    try {
        abort(200, 'Bar', ['Foo' => 'Bar']);
    } catch (ConsoleExceptionContract $consoleException) {
    }

    expect($consoleException)->toBeInstanceOf(ConsoleExceptionContract::class)
        ->and($consoleException->getExitCode())->toEqual(200)
        ->and($consoleException->getMessage())->toEqual('Bar')
        ->and($consoleException->getHeaders())->toEqual(['Foo' => 'Bar']);
});
