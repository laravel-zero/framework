<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Providers\PharBuilt\PharBuilt;

final class PharBuiltEnvironmentTest extends TestCase
{
    public function setUp(): void
    {
        File::put(base_path('.env'), 'CONSUMER_KEY=LOCAL_ENV_VALUE');

        File::makeDirectory(base_path('builds'));

        File::put(base_path('builds/.env'), 'CONSUMER_KEY=PRODUCTION_ENV_VALUE');

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
        $pharBuiltMock = $this->createMock(PharBuilt::class);

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


    }
}
