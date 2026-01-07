<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('adds the components commands to the application', function () {
    $commands = collect(Artisan::all())
        ->map(fn ($command) => get_class($command))
        ->flip();

    collect(
        [
            \Laravel\Mcp\Console\Commands\InspectorCommand::class,
            \Laravel\Mcp\Console\Commands\MakePromptCommand::class,
            \Laravel\Mcp\Console\Commands\MakeResourceCommand::class,
            \Laravel\Mcp\Console\Commands\MakeServerCommand::class,
            \Laravel\Mcp\Console\Commands\MakeToolCommand::class,
            \Laravel\Mcp\Console\Commands\StartCommand::class,
        ]
    )->map(
        function ($commandClass) use ($commands) {
            expect($commands)->toHaveKey($commandClass);
        }
    );
});
