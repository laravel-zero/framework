<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\Composer;

class DotenvInstallTest extends TestCase
{
    public function tearDown()
    {
        File::delete(base_path('.env'));
        File::delete(base_path('.env.example'));
    }

    /** @test */
    public function it_requires_packages(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->expects($this->once())->method('require')->with('vlucas/phpdotenv');
        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('install:dotenv');
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->expects($this->once())->method('require')->with('vlucas/phpdotenv');
        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('install:dotenv');

        $this->assertTrue(File::exists(base_path('.env')));
        $this->assertTrue(File::exists(base_path('.env.example')));
    }
}
