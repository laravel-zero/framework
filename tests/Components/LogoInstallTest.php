<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

afterEach(function () {
    File::delete(config_path('logo.php'));
});

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->once())
        ->method('require')
        ->with('laminas/laminas-text "^2.10"');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'logo']);
});

it('copies the required stubs', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'logo']);

    expect(File::exists(config_path('logo.php')))->toBeTrue();
});
