<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorder;

final class RecordCommandsTest extends TestCase
{
    public function testThatRecorderWorks()
    {
        app(CommandRecorder::class)->record('dummy:command', ['foo' => 'bar']);

        $this->assertCommandNotCalled('foo:bar');
        $this->assertCommandNotCalled('dummy:command');
        $this->assertCommandCalled('dummy:command', ['foo' => 'bar']);
    }

    public function testThatRecorderTracksKernelAndCommand()
    {
        Artisan::call('app:install');

        $this->assertCommandNotCalled('app:install', ['foo' => 'bar']);
        $this->assertCommandCalled('app:install');
    }

    /**
     * Assert that a command was called using the provided parameters.
     *
     * @param $command
     * @param array $parameters
     */
    protected function assertCommandCalled($command, $parameters = [])
    {
        TestCase::assertTrue(
            app(CommandRecorder::class)->commandWasCalled($command, $parameters),
            'Failed asserting that \'' . $command . '\' was called with the provided parameters.'
        );
    }

    /**
     * Assert that a command was NOT called using the provided parameters.
     *
     * @param $command
     * @param array $parameters
     */
    protected function assertCommandNotCalled($command, $parameters = [])
    {
        TestCase::assertFalse(
            app(CommandRecorder::class)->commandWasCalled($command, $parameters),
            'Failed asserting that \'' . $command . '\' was NOT called with the provided parameters.'
        );
    }
}
