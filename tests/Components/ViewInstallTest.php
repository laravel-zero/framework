<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

afterEach(function () {
    File::deleteDirectory(base_path('resources'));
    File::deleteDirectory(base_path('storage/app'));
    File::deleteDirectory(base_path('storage/framework/views'));
});

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    $composerMock->expects($this->exactly(1))
        ->method('require')
        ->withConsecutive(
            ['illuminate/view "^9.0"', false],
        );

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'view']);
});

it('copies the required stubs', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'view']);

    expect(File::exists(config_path('view.php')))->toBeTrue();
    expect(File::exists(base_path('resources')))->toBeTrue();
    expect(File::exists(base_path('resources/views')))->toBeTrue();
    expect(File::exists(base_path('storage/app/.gitignore')))->toBeTrue();
    expect(File::exists(base_path('storage/framework/views/.gitignore')))->toBeTrue();
});
