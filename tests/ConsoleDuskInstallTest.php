<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\Composer;

class ConsoleDuskInstallTest extends TestCase
{
    /** @test */
    public function it_requires_packages(): void
    {
        $composerMock = $this->createMock(Composer::class);

        $composerMock->expects($this->once())
            ->method('require')
            ->with('nunomaduro/laravel-console-dusk');

        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:install', ['component' => 'console-dusk']);
    }
}
