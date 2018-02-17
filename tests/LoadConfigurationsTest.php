<?php

namespace Tests;

use App\Commands\FakeFooCommand;
use App\Commands\FakeDefaultCommand;
use App\OtherCommands\FakeOtherCommand;
use Illuminate\Support\Facades\Artisan;
use App\HiddenCommands\FakeHiddenCommand;

class LoadConfigurationsTest extends TestCase
{
    /** @test */
    public function it_reads_configuration_files()
    {
        $this->assertSame('Test name', Artisan::getName());
        $this->assertSame('Test version', $this->app->version());
        $this->assertEquals(
            $this->app->environment(),
            'development'
        );
    }

    /** @test */
    public function it_reads_commands()
    {
        $commands = [
            FakeDefaultCommand::class,
            FakeFooCommand::class,
            FakeOtherCommand::class,
            FakeHiddenCommand::class,
        ];

        $appCommands = collect(Artisan::all())->map(
            function ($command) {
                return get_class($command);
            }
        )->toArray();

        foreach ($commands as $command) {
            $this->assertContains($command, $appCommands);
        }
    }

    /** @test */
    public function it_allows_hidden_commands()
    {
        $this->assertTrue(Artisan::all()['fake:hidden']->isHidden());
    }

    /** @test */
    public function it_allows_remove_commands()
    {
        $this->assertArrayNotHasKey('fake:removed', Artisan::all());
    }
}
