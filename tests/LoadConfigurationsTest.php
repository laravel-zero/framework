<?php

namespace Tests;

use App\Commands\FakeFooCommand;
use App\Commands\FakeDefaultCommand;
use Illuminate\Support\Facades\Config;
use App\OtherCommands\FakeOtherCommand;
use Illuminate\Support\Facades\Artisan;

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
}
