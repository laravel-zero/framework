<?php

namespace Tests;

use Illuminate\Support\Facades\File;

class BuilderTest extends TestCase
{
    public function tearDown()
    {
        File::deleteDirectory(base_path('builds'));
    }

    /** @test */
    public function it_builds_the_application(): void
    {
        $this->app->call('app:build');

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

        $this->app->call('app:build', ['name' => 'zonda']);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));
    }
}
