<?php

declare(strict_types=1);

use Illuminate\Foundation\Console\JobMakeCommand;
use Illuminate\Queue\Console\FailedTableCommand;
use Illuminate\Queue\Console\FlushFailedCommand;
use Illuminate\Queue\Console\ForgetFailedCommand;
use Illuminate\Queue\Console\ListenCommand;
use Illuminate\Queue\Console\ListFailedCommand;
use Illuminate\Queue\Console\RestartCommand;
use Illuminate\Queue\Console\RetryCommand;
use Illuminate\Queue\Console\TableCommand;
use Illuminate\Queue\Console\WorkCommand;
use Illuminate\Support\Facades\Artisan;

it('adds the components commands to the application', function () {
    $commands = collect(Artisan::all())
        ->map(
            fn ($command) => get_class($command)
        )
        ->flip();

    collect(
        [
            TableCommand::class,
            FailedTableCommand::class,
            JobMakeCommand::class,
            WorkCommand::class,
            RetryCommand::class,
            ListenCommand::class,
            RestartCommand::class,
            ListFailedCommand::class,
            FlushFailedCommand::class,
            ForgetFailedCommand::class,
        ]
    )->map(
        fn ($commandClass) => expect($commands)->toHaveKey($commandClass)
    );
});
