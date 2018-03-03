<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\Composer;

class BuilderTest extends TestCase
{
    public function tearDown()
    {
        File::deleteDirectory(base_path('builds'));
    }

    /** @test */
    public function it_builds_the_application(): void
    {
        $composerMock = $this->createMock(Composer::class);
        $composerMock->expects($this->exactly(4))->method('install');
        $this->app->instance(Composer::class, $composerMock);

        Artisan::call('app:build');

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

        Artisan::call('app:build', ['name' => 'zonda']);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));
    }
}
