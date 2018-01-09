<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\Composer;
use LaravelZero\Framework\Commands\Component\Vlucas\Phpdotenv\Installer;

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

        app()->instance(Composer::class, $composerMock);

        $this->app->add(app(Installer::class));

        $this->app->call('install:dotenv');
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $this->addDotenvCommand();

        $this->app->call('install:dotenv');

        $this->assertTrue(File::exists(base_path('.env')));
        $this->assertTrue(File::exists(base_path('.env.example')));
    }

    private function addDotenvCommand(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->method('require');
        app()->instance(Composer::class, $composerMock);

        $this->app->add(app(Installer::class));
    }
}
