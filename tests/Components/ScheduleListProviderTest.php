<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('adds the components commands to the application', function () {
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
            expect($commands)->toHaveKey($commandClass);
        }
    );
});
