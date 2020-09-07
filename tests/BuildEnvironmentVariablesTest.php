<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Bootstrap\BuildLoadEnvironmentVariables;
use LaravelZero\Framework\Providers\Build\Build;

beforeEach(function () {
    File::put(base_path('.env'), 'CONSUMER_KEY=LOCAL_ENV_VALUE');

    File::makeDirectory(base_path('builds'));

    File::put(base_path('builds/.env'), 'CONSUMER_KEY=PRODUCTION_ENV_VALUE');

    $this->refreshApplication();
});

afterEach(function () {
    File::delete(base_path('.env'));

    File::deleteDirectory(base_path('builds'));
});

it('can retrieve an environment variable in local', function () {
    Artisan::call('fake:environmentValue');

    expect(Artisan::output())->toContain('LOCAL_ENV_VALUE');
});

it('can retrieve an environment variable in production', function () {
    $buildMock = $this->createMock(Build::class, ['shouldUseEnvironmentFile', 'getDirectoryPath', 'environmentFile']);

    $buildMock->expects($this->atLeastOnce())
        ->method('shouldUseEnvironmentFile')
        ->willReturn(true);

    $buildMock->expects($this->atLeastOnce())
        ->method('getDirectoryPath')
        ->willReturn(base_path('builds'));

    $buildMock->expects($this->atLeastOnce())
        ->method('environmentFile')
        ->willReturn('.env');

    $this->app->instance(Build::class, $buildMock);

    (new BuildLoadEnvironmentVariables($buildMock))->bootstrap(app());

    expect(env('CONSUMER_KEY'))->toEqual('PRODUCTION_ENV_VALUE');
});
