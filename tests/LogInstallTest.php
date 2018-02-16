<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\Composer;

class LogInstallTest extends TestCase
{
    public function tearDown()
    {
        File::delete(config_path('logging.php'));
    }

    /** @test */
    public function it_requires_packages(): void
    {
        $composerMock = $this->createMock(Composer::class);

        $composerMock->expects($this->once())
            ->method('require')
            ->with('illuminate/log "5.6.*"');

        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:install', ['component' => 'log']);
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->method('require');
        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:install', ['component' => 'log']);

        $this->assertTrue(File::exists(config_path('logging.php')));
    }
}
