<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;

final class QueueProviderTest extends TestCase
{
    /** @test */
    public function it_adds_commands(): void
    {
        $commands = collect(Artisan::all())
            ->map(
                function ($command) {
                    return get_class($command);
                }
            )
            ->flip();

        collect(
            [
                \Illuminate\Queue\Console\TableCommand::class,
                \Illuminate\Queue\Console\FailedTableCommand::class,
                \Illuminate\Foundation\Console\JobMakeCommand::class,
                \Illuminate\Queue\Console\WorkCommand::class,
                \Illuminate\Queue\Console\RetryCommand::class,
                \Illuminate\Queue\Console\ListenCommand::class,
                \Illuminate\Queue\Console\RestartCommand::class,
                \Illuminate\Queue\Console\ListFailedCommand::class,
                \Illuminate\Queue\Console\FlushFailedCommand::class,
                \Illuminate\Queue\Console\ForgetFailedCommand::class,
            ]
        )->map(
            function ($commandClass) use ($commands) {
                $this->assertArrayHasKey($commandClass, $commands);
            }
        );
    }
}
