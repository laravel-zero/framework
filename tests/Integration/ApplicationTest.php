<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Container\Container;
use Tests\Application\FakeExtraCommand;
use Tests\Application\FakeDefaultCommand;
use Illuminate\Contracts\Config\Repository;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_binds_core_alias(): void
    {
        $container = ($application = $this->createApplication())
            ->getContainer();

        $this->assertSame($container, Container::getInstance());
        $this->assertSame($container, $container->make('app'));
        $this->assertSame($container, $container->make(Container::class));
        $this->assertSame($application, $container->make(ApplicationContract::class));
        $this->assertInstanceOf(Repository::class, $container->make('config'));
    }

    /** @test */
    public function it_configures_using_configuration()
    {
        // @todo Test production config.
        $app = $this->createApplication();

        $this->assertSame('Test name', $app->getName());
        $this->assertSame('Test version', $app->getVersion());

        $commands = [
            FakeDefaultCommand::class,
            FakeExtraCommand::class,
        ];

        $appCommands = collect($app->all())
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
    public function it_register_service_providers()
    {
        $app = $this->createApplication();

        $this->assertSame('bar', $app->getContainer()->make('foo'));
    }
}
