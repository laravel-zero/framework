<?php

namespace Tests;

use Illuminate\Support\Facades\File;

class CommandMakerTest extends TestCase
{
    public function tearDown()
    {
        File::delete(app_path('commands' . DIRECTORY_SEPARATOR . 'FooCommand.php'));
    }

    /** @test */
    public function it_builds_the_application(): void
    {
        $this->app->call('make:command', ['name' => 'FooCommand']);

        $file = app_path('commands'.DIRECTORY_SEPARATOR.'FooCommand.php');

        $this->assertTrue(File::exists($file));
        $this->assertContains('class FooCommand extends Command', File::get($file));
    }
}
