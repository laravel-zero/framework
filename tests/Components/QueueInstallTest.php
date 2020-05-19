<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

afterEach(function () {
    File::deleteDirectory(database_path());
    File::delete(base_path('.gitignore'));
    touch(base_path('.gitignore'));
});

it('installs the required packages', function () {
    $composerMock = $this->createMock(ComposerContract::class);

    // database, queue, bus...
    $composerMock->expects($this->exactly(4))
        ->method('require');

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'queue']);
});
