<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class DatabaseInstallTest extends TestCase
{
    public function tearDown(): void
    {
        File::deleteDirectory(database_path());
        File::delete(base_path('.gitignore'));
        touch(base_path('.gitignore'));
    }

    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->exactly(2))
            ->method('require')
            ->withConsecutive(
                ['illuminate/database "^6.0"', false],
                ['fzaninotto/faker "^1.4"', true]
            );

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

        $this->assertTrue(Str::contains(File::get(base_path('.gitignore')), '/database/database.sqlite'));
    }

    private function mockComposer(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $composerMock->method('require');
        $this->app->instance(ComposerContract::class, $composerMock);
    }
}
