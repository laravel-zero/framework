<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('adds the components commands to the application', function () {
    $commands = collect(Artisan::all())
        ->map(fn ($command) => get_class($command))
        ->flip();

    collect(
        [
            \Illuminate\Database\Console\Seeds\SeedCommand::class,
            \Illuminate\Database\Console\Seeds\SeederMakeCommand::class,
            \Illuminate\Database\Console\Migrations\FreshCommand::class,
            \Illuminate\Database\Console\Migrations\InstallCommand::class,
            \Illuminate\Database\Console\Migrations\MigrateCommand::class,
            \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class,
            \Illuminate\Database\Console\Migrations\RefreshCommand::class,
            \Illuminate\Database\Console\Migrations\ResetCommand::class,
            \Illuminate\Database\Console\Migrations\RollbackCommand::class,
            \Illuminate\Database\Console\Migrations\StatusCommand::class,
            \Illuminate\Database\Console\Factories\FactoryMakeCommand::class,
            \Illuminate\Database\Console\WipeCommand::class,
        ]
    )->map(
        function ($commandClass) use ($commands) {
            expect($commands)->toHaveKey($commandClass);
        }
    );
});
