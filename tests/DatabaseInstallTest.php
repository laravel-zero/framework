<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class DatabaseInstallTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(database_path('database.sqlite'));
        File::delete(database_path('migrations'));
        File::delete(database_path('factories'));
        File::delete(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php'));
        File::delete(base_path('.gitignore'));
        touch(base_path('.gitignore'));
    }

    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->once())
            ->method('require')
            ->with('illuminate/database "5.8.*"');

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'database']);
    }

    public function testCopyStubs(): void
    {
        $this->mockComposer();

        Artisan::call('app:install', ['component' => 'database']);

        $this->assertTrue(File::exists(config_path('database.php')));
        $this->assertTrue(File::exists(database_path('database.sqlite')));
        $this->assertTrue(File::exists(database_path('migrations')));
        $this->assertTrue(File::exists(database_path('factories')));
        $this->assertTrue(File::exists(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')));
    }

    public function testNewGitIgnoreLines(): void
    {
        $this->mockComposer();

        Artisan::call('app:install', ['component' => 'database']);

        $this->assertTrue(str_contains(File::get(base_path('.gitignore')), '/database/database.sqlite'));
    }

    private function mockComposer(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $composerMock->method('require');
        $this->app->instance(ComposerContract::class, $composerMock);
    }
}
