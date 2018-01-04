<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\Composer;
use LaravelZero\Framework\Commands\Component\Illuminate\Database\Installer;

class InstallDatabaseTest extends TestCase
{
    public function tearDown()
    {
        File::delete(config_path('database.php'));
        File::delete(database_path('database.sqlite'));
        File::delete(database_path('migrations'));
        File::delete(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php'));
    }

    /** @test */
    public function it_requires_packages(): void
    {
        $composerMock = $this->createMock(Composer::class);

        $composerMock->expects($this->once())->method('require')->with('illuminate/database "5.5.*"');

        app()->instance(Composer::class, $composerMock);

        $this->app->add(app(Installer::class));

        $this->app->call('install:database');
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $this->addDatabaseCommand();

        $this->app->call('install:database');

        $this->assertTrue(File::exists(config_path('database.php')));
        $this->assertTrue(File::exists(database_path('database.sqlite')));
        $this->assertTrue(File::exists(database_path('migrations')));
        $this->assertTrue(File::exists(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')));
    }

    private function addDatabaseCommand(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->method('require');
        app()->instance(Composer::class, $composerMock);

        $this->app->add(app(Installer::class));
    }
}
