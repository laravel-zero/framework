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
    assertSame('production', $this->app->environment());

    assertArrayNotHasKey('test', Artisan::all());
});
