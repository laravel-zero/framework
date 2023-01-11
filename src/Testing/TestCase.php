<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Testing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Facade;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use NunoMaduro\Collision\ArgumentFormatter;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        /** @phpstan-ignore-next-line */
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();

        foreach ($this->afterApplicationCreatedCallbacks as $callback) {
            call_user_func($callback);
        }

        Facade::clearResolvedInstances();

        if (class_exists(Model::class)) {
            Model::setEventDispatcher($this->app['events']);
        }

        $this->setUpHasRun = true;
    }

    /**
     * Assert that a command was called using the given arguments.
     */
    protected function assertCommandCalled(string $command, array $arguments = []): void
    {
        $argumentsAsString = (new ArgumentFormatter)->format($arguments);
        $recorder = app(CommandRecorderRepository::class);

        static::assertTrue($recorder->exists($command, $arguments),
            'Failed asserting that \''.$command.'\' was called with the given arguments: '.$argumentsAsString);
    }

    /**
     * Assert that a command was not called using the given arguments.
     */
    protected function assertCommandNotCalled(string $command, array $arguments = []): void
    {
        $argumentsAsString = (new ArgumentFormatter)->format($arguments);
        $recorder = app(CommandRecorderRepository::class);

        static::assertFalse($recorder->exists($command, $arguments),
            'Failed asserting that \''.$command.'\' was not called with the given arguments: '.$argumentsAsString);
    }
}
