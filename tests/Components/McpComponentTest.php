<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->exactly(1))
        ->method('require');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'mcp']);
});

it('publishes the required files', function () {
    Artisan::call('app:install', ['component' => 'mcp']);

    expect(file_exists($this->app->basePath('bootstrap'.DIRECTORY_SEPARATOR.'ai.php')))
        ->toBeTrue();
});
