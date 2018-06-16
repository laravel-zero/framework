<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\NullOutput;
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
        $this->app->instance(Composer::class, $composerMock);

        $output = new class() extends NullOutput {
            public function section()
            {
                return new class() extends NullOutput {
                    public function clear()
                    {
                    }
                };
            }
        };


        Artisan::call('app:build', [], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

        Artisan::call('app:build', ['name' => 'zonda'], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));
    }
}
