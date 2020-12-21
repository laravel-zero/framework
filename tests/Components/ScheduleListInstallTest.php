<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->once())
        ->method('require')
        ->with('hmazter/laravel-schedule-list "^2.2.1"');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'schedule-list']);
});

it('copies the required stubs', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'schedule-list']);

    expect(File::exists(config_path('schedule-list.php')))->toBeTrue();
});
