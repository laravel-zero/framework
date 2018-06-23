<?php

declare(strict_types=1);

namespace Tests;

use function touch;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class DotenvInstallTest extends TestCase
{
    public function tearDown()
    {
        File::delete(base_path('.env'));
        File::delete(base_path('.env.example'));
        File::delete(base_path('.gitignore'));
        touch(base_path('.gitignore'));
    }

    /** @test */
    public function it_requires_packages(): void
    {
        $this->mockComposer();

        Artisan::call('app:install', ['component' => 'dotenv']);
    }

    /** @test */
    public function it_copy_stubs(): void
    {
        $this->mockComposer();

        Artisan::call('app:install', ['component' => 'dotenv']);

        $this->assertTrue(File::exists(base_path('.env')));
        $this->assertTrue(File::exists(base_path('.env.example')));
    }

    /** @test */
    public function it_adds_line_on_gitignore(): void
    {
        $this->mockComposer();

        Artisan::call('app:install', ['component' => 'dotenv']);

        $this->assertTrue(str_contains(File::get(base_path('.gitignore')), '.env'));
    }

    private function mockComposer(): void
    {
        $composerMock = $this->createMock(ComposerContract::class);
        $composerMock->expects($this->once())
            ->method('require')
            ->with('vlucas/phpdotenv');
        $this->app->instance(ComposerContract::class, $composerMock);
    }
}
