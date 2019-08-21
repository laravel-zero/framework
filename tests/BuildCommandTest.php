<?php

declare(strict_types=1);

namespace Tests;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\NullOutput;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class BuildCommandTest extends TestCase
{
    public function tearDown(): void
    {
        File::deleteDirectory(base_path('builds'));
        File::delete(base_path('application.phar'));
    }

    public function testBuild(): void
    {
        $this->mockComposer();

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

        Artisan::call('app:build', ['--no-interaction' => true], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

        Artisan::call('app:build', ['name' => 'zonda', '--no-interaction' => true], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));

        Artisan::call('app:build', ['name' => 'version', '--no-interaction' => true, '--build-version' => 'v0'], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'version')));
    }

    public function testConfigStateAfterBuild(): void
    {
        $this->mockComposer();

        $contents = File::get(config_path('app.php'));

        $output = new class() extends NullOutput {
            public function section()
            {
                return new class() extends NullOutput {
                    public function clear()
                    {
                        throw new Exception('Foo bar');
                    }
                };
            }
        };

        $this->expectException(Exception::class);

        Artisan::call('app:build', ['--no-interaction' => true], $output);

        $this->assertEquals($contents, File::get(config_path('app.php')));
    }

    private function mockComposer(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $this->app->instance(ComposerContract::class, $composerMock);
    }
}
