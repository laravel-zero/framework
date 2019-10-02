<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use LaravelZero\Framework\Bootstrap\BuildLoadEnvironmentVariables;
use LaravelZero\Framework\Providers\Build\Build;

final class BuildEnvironmentVariablesTest extends TestCase
{
    public function setUp(): void
    {
        file_put_contents(__DIR__.'/Application/.env', 'CONSUMER_KEY=LOCAL_ENV_VALUE');

        mkdir(__DIR__.'/Application/builds');

        file_put_contents(__DIR__.'/Application/builds/.env', 'CONSUMER_KEY=PRODUCTION_ENV_VALUE');

        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        File::delete(base_path('.env'));

        File::deleteDirectory(base_path('builds'));
    }

    public function testLocalEnvironment(): void
    {
        Artisan::call('fake:environmentValue');

        $this->assertTrue(Str::contains(Artisan::output(), 'LOCAL_ENV_VALUE'));
    }

    public function testProductionEnvironment(): void
    {
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

        $this->assertEquals(env('CONSUMER_KEY'), 'PRODUCTION_ENV_VALUE');
    }
}
