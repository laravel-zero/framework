<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class LogoInstallTest extends TestCase
{
    public function tearDown()
    {
        File::delete(config_path('logo.php'));
    }

    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->once())
            ->method('require')
            ->with('zendframework/zend-text "^2.7"');

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'logo']);
    }

    public function testCopyStubs(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $composerMock->method('require');
        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'logo']);

        $this->assertTrue(File::exists(config_path('logo.php')));
    }
}
