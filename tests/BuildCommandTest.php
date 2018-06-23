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
    public function tearDown()
    {
        File::deleteDirectory(base_path('builds'));
        File::delete(base_path('application.phar'));
    }

    /** @test */
    public function it_builds_the_application(): void
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

        Artisan::call('app:build', [], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

        Artisan::call('app:build', ['name' => 'zonda'], $output);

        $this->assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));
    }

    /** @test */
    public function it_maintains_the_state_of_the_config(): void
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

        Artisan::call('app:build', [], $output);

        $this->assertEquals($contents, File::get(config_path('app.php')));
    }

    private function mockComposer(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $this->app->instance(ComposerContract::class, $composerMock);
    }
}
