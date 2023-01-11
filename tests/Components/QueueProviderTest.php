<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('adds the components commands to the application', function () {
    $commands = collect(Artisan::all())
        ->map(
            fn ($command) => get_class($command)
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
        fn ($commandClass) => expect($commands)->toHaveKey($commandClass)
    );
});
