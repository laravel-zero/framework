<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\Composer;

class DatabaseInstallTest extends TestCase
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

        $composerMock->expects($this->once())->method('require')->with('illuminate/database "5.6.*"');

        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:install', ['component' => 'database']);
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->method('require');
        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:install', ['component' => 'database']);

        $this->assertTrue(File::exists(config_path('database.php')));
        $this->assertTrue(File::exists(database_path('database.sqlite')));
        $this->assertTrue(File::exists(database_path('migrations')));
        $this->assertTrue(File::exists(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')));
    }
}
