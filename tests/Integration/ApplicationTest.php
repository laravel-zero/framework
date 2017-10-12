<?php

namespace Tests\Integration;

use Tests\TestCase;
use Tests\FakeExtraCommand;
use Tests\FakeDefaultCommand;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use LaravelZero\Framework\Contracts\Application as ApplicationContract;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_binds_core_alias(): void
    {
        $container = ($application = $this->createApplication())
            ->getContainer();

        $this->assertTrue(Container::getInstance() === $container);
        $this->assertTrue($container->make('app') === $container);
        $this->assertTrue($container->make(Container::class) === $container);
        $this->assertTrue($container->make(ApplicationContract::class) === $application);
        $this->assertInstanceOf(Repository::class, $container->make('config'));
    }

    /** @test */
    public function it_configures_using_configuration()
    {
        // @todo Test production config.
        $app = $this->createApplication();

        $this->assertTrue($app->getName() === 'Test name');
        $this->assertTrue($app->getVersion() === 'Test version');

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
            $this->assertTrue(in_array($command, $appCommands));
        }
    }

    /** @test */
    public function it_register_service_providers()
    {
        $app = $this->createApplication();

        $this->assertTrue(
            $app->getContainer()
                ->make('foo') === 'bar'
        );
    }
}
