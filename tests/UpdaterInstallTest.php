<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class UpdaterInstallTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(config_path('updater.php'));
    }

    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->once())->method('require')->with('padraic/phar-updater "^1.0.6"');

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'updater']);
    }

    public function testCopyStubs(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $composerMock->method('require');
        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'updater']);

        $this->assertTrue(File::exists(config_path('updater.php')));
    }
}
