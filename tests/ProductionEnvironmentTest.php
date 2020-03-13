<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

final class ProductionEnvironmentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        File::copy(base_path('production-config-app.php'), config_path('app.php'));

        $this->refreshApplication();
    }

    /** @test */
    public function it_removes_development_only_commands_in_production(): void
    {
        $this->assertSame('production', $this->app->environment());

        $this->assertArrayNotHasKey('test', Artisan::all());
    }

    public function tearDown(): void
    {
        File::copy(base_path('save-config-app.php'), config_path('app.php'));
    }
}
