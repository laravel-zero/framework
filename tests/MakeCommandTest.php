<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

final class MakeCommandTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php'));
        File::delete(app_path('Commands/PublishedStubbedCommand.php'));
        File::delete(base_path('stubs/console.stub'));
    }

    public function testCommandCreation(): void
    {
        Artisan::call('make:command', ['name' => 'FooCommand']);

        $file = app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php');

        $this->assertTrue(File::exists($file));
        $this->assertStringContainsString('class FooCommand extends Command', File::get($file));
        $this->assertStringContainsString('use LaravelZero\Framework\Commands\Command;', File::get($file));
    }

    /** @test */
    public function it_can_make_a_command_from_published_stub(): void
    {
        $consoleStubPath = base_path('stubs/console.stub');

        Artisan::call('stub:publish');
        $this->assertFileExists($consoleStubPath);

        File::put(
            $consoleStubPath,
            str_replace('extends Command', 'extends Command implements FooInterface', File::get($consoleStubPath))
        );

        Artisan::call('make:command', ['name' => 'PublishedStubbedCommand']);
        $file = app_path('Commands/PublishedStubbedCommand.php');

        $this->assertFileExists($file);
        $this->assertStringContainsString('class PublishedStubbedCommand extends Command implements FooInterface', File::get($file));
        $this->assertStringContainsString('use LaravelZero\Framework\Commands\Command;', File::get($file));
    }
}
