<?php

namespace Tests;

use Illuminate\Support\Facades\File;

class RenamerTest extends TestCase
{
    public function tearDown()
    {
        File::copy(base_path('save-composer.json'), base_path('composer.json'));
        File::copy(base_path('save-application'), base_path('application'));
        File::delete(base_path('zonda'));
    }

    /** @test */
    public function it_renames_the_binary(): void
    {
        $this->app->call('app:rename', ['name' => 'zonda']);

        $this->assertTrue(File::exists(base_path('zonda')));
        $this->assertContains('"bin": ["zonda"]', File::get(base_path('composer.json')));
    }
}
