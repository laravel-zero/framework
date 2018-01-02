<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Config\Repository;
use Tests\Application\App\Commands\FakeFooCommand;
use Tests\Application\App\Commands\FakeDefaultCommand;
use Tests\Application\App\OtherCommands\FakeOtherCommand;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_binds_core_alias(): void
    {
        $container = $this->app->getContainer();

        $this->assertSame($container, Container::getInstance());
        $this->assertSame($container, $container->make('app'));
        $this->assertSame($container, $container->make(Container::class));
        $this->assertSame($this->app, $container->make(ApplicationContract::class));
        $this->assertInstanceOf(Repository::class, $container->make('config'));
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'app', $container->make('path'));
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'storage', $container->make('path.storage'));
    }

    /** @test */
    public function it_reads_configuration_files()
    {
        // @todo Test production config.

        $this->assertSame('Test name', $this->app->getName());
        $this->assertSame('Test version', $this->app->getVersion());
        $this->assertEquals(
            $this->app->getContainer()
                ->environment(),
            'development'
        );

        $this->assertEquals(
            $this->app->getContainer()
                ->make('config')
                ->get('foo.bar'),
            10
        );
    }

    /** @test */
    public function it_register_commands()
    {
        $commands = [
            FakeDefaultCommand::class,
            FakeFooCommand::class,
            FakeOtherCommand::class,
        ];

        $appCommands = collect($this->app->all())
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

    /** @test */
    public function it_defines_core_constants()
    {
        $this->assertEquals(ARTISAN_BINARY, base_path('phpunit'));
    }

    /** @test */
    public function it_allows_using_facades()
    {
        $this->assertEquals(
            Config::get('app.name'),
            'Test name'
        );
    }

    /** @test */
    public function it_register_service_providers()
    {
        $app = $this->createApplication();

        $this->assertSame(
            'bar',
            $app->getContainer()
                ->make('foo')
        );
    }

    /** @test */
    public function it_guards_the_running_command()
    {
        $app = $this->createApplication();

        $app->call('fake:default');

        $this->assertInstanceOf(FakeDefaultCommand::class, $app->getRunningCommand());
    }
}
