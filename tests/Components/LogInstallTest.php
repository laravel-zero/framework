<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

afterEach(function () {
    File::delete(config_path('logging.php'));
});

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->once())
        ->method('require')
        ->with('illuminate/log "^10.0"');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'log']);
});

it('copies the required stubs', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'log']);

    expect(File::exists(config_path('logging.php')))->toBeTrue();
});
