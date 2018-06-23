<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

final class RenameCommandTest extends TestCase
{
    public function tearDown()
    {
        File::copy(base_path('save-composer.json'), base_path('composer.json'));
        File::copy(base_path('save-application'), base_path('application'));
        File::copy(base_path('save-config-app.php'), config_path('app.php'));
        File::delete(base_path('zonda'));
    }

    /** @test */
    public function it_renames_the_binary(): void
    {
        Artisan::call('app:rename', ['name' => 'zonda']);

        $this->assertTrue(File::exists(base_path('zonda')));
        $this->assertContains('"bin": ["zonda"]', File::get(base_path('composer.json')));
        $this->assertContains("'name' => 'Zonda'", File::get(config_path('app.php')));
    }
}
