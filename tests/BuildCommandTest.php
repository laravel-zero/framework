<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;
use Symfony\Component\Console\Output\NullOutput;

afterEach(function () {
    File::deleteDirectory(base_path('builds'));
    File::delete(base_path('application.phar'));
});

it('can build the application', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $this->app->instance(ComposerContract::class, $composerMock);

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

    assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'application')));

    Artisan::call('app:build', ['name' => 'zonda', '--no-interaction' => true], $output);

    assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'zonda')));

    Artisan::call('app:build', ['name' => 'version', '--no-interaction' => true, '--build-version' => 'v0'], $output);

    assertTrue(File::exists(base_path('builds'.DIRECTORY_SEPARATOR.'version')));
});

it('reverts the config state after a build', function () {
    $composerMock = $this->createMock(ComposerContract::class);
    $this->app->instance(ComposerContract::class, $composerMock);

    $contents = File::get(config_path('app.php'));

    $output = new class() extends NullOutput {
        public function section()
        {
            return new class() extends NullOutput {
                public function clear()
                {
                    throw new RuntimeException('Foo bar');
                }
            };
        }
    };

    $exception = null;

    try {
        Artisan::call('app:build', ['--no-interaction' => true], $output);
    } catch (RuntimeException $exception) {
    }

    assertInstanceOf(RuntimeException::class, $exception);
    assertEquals('Foo bar', $exception->getMessage());

    assertEquals($contents, File::get(config_path('app.php')));
})->skip('This test is currently broken (investigating)');
