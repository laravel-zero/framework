<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;

class DatabaseProviderTest extends TestCase
{
    /** @test */
    public function it_adds_commands(): void
    {
        $commands = collect(Artisan::all())->map(function ($command) {
            return get_class($command);
        })->flip();

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
            ]
        )->map(
            function ($commandClass) use ($commands) {
                $this->assertArrayHasKey($commandClass, $commands);
            }
        );
    }
}
