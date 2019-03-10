<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

final class MakeCommandTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php'));
    }

    public function testCommandCreation(): void
    {
        Artisan::call('make:command', ['name' => 'FooCommand']);

        $file = app_path('Commands'.DIRECTORY_SEPARATOR.'FooCommand.php');

        $this->assertTrue(File::exists($file));
        $this->assertStringContainsString('class FooCommand extends Command', File::get($file));
    }
}
