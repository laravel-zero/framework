<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\FakeExtraCommand;
use Tests\FakeDefaultCommand;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;

class ApplicationTest extends TestCase
{
    /** @test */
    public function it_proxies_all_calls_into_container(): void
    {
        $app = $this->createApplication();

        $app->bind(
            'foo',
            function () {
                return 'bar';
            }
        );

        $this->assertTrue('bar' === $app->make('foo'));
    }

    /** @test */
    public function it_proxies_array_access_into_container(): void
    {
        $app = $this->createApplication();

        $app['bar'] = function () {
            return 'foo';
        };
        $this->assertTrue(isset($app['bar']));
        $this->assertEquals('foo', $app['bar']);
        unset($app['bar']);
        $this->assertFalse(isset($app['bar']));
    }

    /** @test */
    public function it_binds_core_alias(): void
    {
        $container = $this->createApplication()
            ->getContainer();

        $this->assertTrue(Container::getInstance() === $container);
        $this->assertTrue($container->make('app') === $container);
        $this->assertTrue($container->make(Container::class) === $container);
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
