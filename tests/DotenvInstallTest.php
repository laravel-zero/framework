<?php

declare(strict_types=1);

namespace Tests;

use function touch;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Contracts\Providers\ComposerContract;

final class DotenvInstallTest extends TestCase
{
    public function tearDown(): void
    {
        File::delete(base_path('.env'));
        File::delete(base_path('.env.example'));
        File::delete(base_path('.gitignore'));
        touch(base_path('.gitignore'));
    }

    public function testCopyStubs(): void
    {
        Artisan::call('app:install', ['component' => 'dotenv']);

        $this->assertTrue(File::exists(base_path('.env')));
        $this->assertTrue(File::exists(base_path('.env.example')));
    }

    public function testNewGitIgnoreLines(): void
    {
        Artisan::call('app:install', ['component' => 'dotenv']);

        $this->assertTrue(str_contains(File::get(base_path('.gitignore')), '.env'));
    }
}
