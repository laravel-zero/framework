<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class HttpInstallTest extends TestCase
{
    public function testRequiredPackages(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);

        $composerMock->expects($this->exactly(2))
            ->method('require')
            ->withConsecutive(
                [$this->equalTo('guzzlehttp/guzzle "^6.3.1"')],
                [$this->equalTo('illuminate/http "^7.0"')]
            );

        $this->app->instance(ComposerContract::class, $composerMock);

        Artisan::call('app:install', ['component' => 'http']);
    }
}
