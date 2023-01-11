<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

afterEach(function () {
    File::delete(base_path('.env'));
    File::delete(base_path('.env.example'));
    File::delete(base_path('.gitignore'));
    touch(base_path('.gitignore'));
});

it('copies the required stubs', function () {
    Artisan::call('app:install', ['component' => 'dotenv']);

    expect(File::exists(base_path('.env')))->toBeTrue()
        ->and(File::exists(base_path('.env.example')))->toBeTrue();
});

it('adds the required lines to the gitignore', function () {
    Artisan::call('app:install', ['component' => 'dotenv']);

    expect(File::get(base_path('.gitignore')))->toContain('.env');
});
