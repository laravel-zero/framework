<?php

declare(strict_types=1);

namespace Tests;

use function get_class;
use Illuminate\Support\Facades\Artisan;

final class ScheduleListProviderTest extends TestCase
{
    public function testAddCommands(): void
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
                \Hmazter\LaravelScheduleList\Console\ListScheduler::class,
            ]
        )->map(
            function ($commandClass) use ($commands) {
                $this->assertArrayHasKey($commandClass, $commands);
            }
        );
    }
}
