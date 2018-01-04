<?php

namespace Tests;

use Illuminate\Console\Command;

class DatabaseProviderTest extends TestCase
{
    /** @test */
    public function it_adds_commands(): void
    {
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
            function ($commandClass) {
                $this->assertInstanceOf(Command::class, $this->app->find(app($commandClass)->getName()));
            }
        );
    }
}
