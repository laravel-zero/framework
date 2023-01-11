<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::copy(base_path('production-config-app.php'), config_path('app.php'));

    $this->refreshApplication();
});

afterEach(function () {
    File::copy(base_path('save-config-app.php'), config_path('app.php'));
});

it('removes development only commands in production', function () {
    expect($this->app->environment())->toBe('production')
        ->and(Artisan::all())->not->toHaveKey('test');
});
