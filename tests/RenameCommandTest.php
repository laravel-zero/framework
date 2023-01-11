<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

afterEach(function () {
    File::copy(base_path('save-composer.json'), base_path('composer.json'));
    File::copy(base_path('save-application'), base_path('application'));
    File::copy(base_path('save-config-app.php'), config_path('app.php'));
    File::delete(base_path('zonda'));
});

it('can rename the application binary', function () {
    Artisan::call('app:rename', ['name' => 'zonda']);

    expect(File::exists(base_path('zonda')))->toBeTrue()
        ->and(File::get(base_path('composer.json')))->toContain('"bin": ["zonda"]')
        ->and(File::get(config_path('app.php')))->toContain("'name' => 'Zonda'");
});
