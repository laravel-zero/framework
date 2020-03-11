<?php

declare(strict_types=1);

namespace Tests;

use App\Commands\FakeDefaultCommand;
use App\Commands\FakeFooCommand;
use App\HiddenCommands\FakeHiddenCommand;
use App\OtherCommands\FakeOtherCommand;
use Illuminate\Support\Facades\Artisan;

final class LoadConfigurationsTest extends TestCase
{
    public function testThatApplicationConfigurationIsAvailable(): void
    {
        $this->assertSame('Application', Artisan::getName());
        $this->assertSame('Test version', $this->app->version());
        $this->assertEquals(
            $this->app->environment(),
            'development'
        );
    }

    public function testAddCommands(): void
    {
        $commands = [
            FakeDefaultCommand::class,
            FakeFooCommand::class,
            FakeOtherCommand::class,
            FakeHiddenCommand::class,
        ];

        $appCommands = collect(Artisan::all())
            ->map(
                function ($command) {
                    return get_class($command);
                }
            )
            ->toArray();

        foreach ($commands as $command) {
            $this->assertContains($command, $appCommands);
        }
    }

    public function testHideCommands(): void
    {
        $this->assertTrue(Artisan::all()['fake:hidden']->isHidden());
    }

    public function testRemoveCommands(): void
    {
        $this->assertArrayNotHasKey('fake:removed', Artisan::all());
    }

    public function testMakeTestCommandIsRemoved(): void
    {
        $this->assertArrayNotHasKey('make:test', Artisan::all());
    }
}
