<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class QueueInstallTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(database_path('database.sqlite'));
        File::delete(database_path('migrations'));
        File::delete(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php'));
        File::delete(base_path('.gitignore'));
        touch(base_path('.gitignore'));
    }

    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        // database, queue, bus...
        $composerMock->expects($this->exactly(3))
            ->method('require');

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'queue']);
    }
}
