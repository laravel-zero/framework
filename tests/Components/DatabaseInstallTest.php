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

    $composerMock->expects($this->exactly(2))
        ->method('require')
        ->withConsecutive(
            ['illuminate/database "^7.0"', false],
            ['fzaninotto/faker "^1.4"', true]
        );

    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'database']);
});

it('copies the required stubs', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'database']);

    expect(File::exists(config_path('database.php')))->toBeTrue();
    expect(File::exists(database_path('database.sqlite')))->toBeTrue();
    expect(File::exists(database_path('migrations')))->toBeTrue();
    expect(File::exists(database_path('factories')))->toBeTrue();
    expect(File::exists(database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')))->toBeTrue();
});

it('adds the required lines to the gitignore', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $composerMock->method('require');
    $this->app->instance(ComposerContract::class, $composerMock);

    Artisan::call('app:install', ['component' => 'database']);

    expect(File::get(base_path('.gitignore')))->toContain('/database/database.sqlite');
});
