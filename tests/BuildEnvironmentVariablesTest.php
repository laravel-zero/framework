<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
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
        $pharBuiltMock = $this->createMock(new Build());

        $pharBuiltMock->expects($this->atLeastOnce())
            ->method('isPharBuilt')
            ->willReturn(true);

        $pharBuiltMock->expects($this->atLeastOnce())
            ->method('pharDirPath')
            ->willReturn(base_path('builds'));

        $pharBuiltMock->expects($this->atLeastOnce())
            ->method('dotEnvWithPharBuilt')
            ->willReturn(base_path('builds/.env'));

        $this->app->instance('phar.built', $pharBuiltMock);

        /*
         * Is it not using the mocked instance??
         */
        Artisan::call('fake:environmentValue');

        $this->assertTrue(Str::contains(Artisan::output(), 'PRODUCTION_ENV_VALUE'));
    }
}
