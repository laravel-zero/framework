<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class ConsoleDuskInstallTest extends TestCase
{
    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->once())
            ->method('require')
            ->with('nunomaduro/laravel-console-dusk');

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'console-dusk']);
    }
}
