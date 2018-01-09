<?php

namespace Tests;

use LaravelZero\Framework\Exceptions\NotImplementedException;
use LaravelZero\Framework\Contracts\Exceptions\ConsoleException;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class ContainerTest extends TestCase
{
    /** @test */
    public function it_has_a_version_getter(): void
    {
        $this->assertEquals($this->app->getContainer()->version(), 'Test version');
    }

    /** @test */
    public function it_has_a_base_path_getter()
    {
        $container = $this->app->getContainer();

        $this->assertEquals(BASE_PATH, $container->basePath());
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'Unit', $container->basePath('Unit'));
    }

    /** @test */
    public function it_has_a_config_path_getter()
    {
        $container = $this->app->getContainer();

        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'config', $container->configPath());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'custom.php',
            $container->configPath('custom.php')
        );
    }

    /** @test */
    public function it_has_a_database_path_getter()
    {
        $container = $this->app->getContainer();

        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'database', $container->databasePath());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations',
            $container->databasePath('migrations')
        );
    }

    /** @test */
    public function it_has_a_lang_path_getter()
    {
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'lang',
            $this->app->getContainer()->langPath()
        );
    }

    /** @test */
    public function it_has_a_resource_path_getter()
    {
        $container = $this->app->getContainer();

        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'resources', $container->resourcePath());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js',
            $container->resourcePath('assets'.DIRECTORY_SEPARATOR.'js')
        );
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'app.php',
            $container->resourcePath('assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'app.php')
        );
    }

    /** @test */
    public function it_has_a_storage_path_getter()
    {
        $container = $this->app->getContainer();

        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'storage', $container->storagePath());
    }

    /** @test */
    public function it_has_environment_getter()
    {
        $container = $this->app->getContainer();

        $this->assertSame('Test version', $container->version());
    }

    /** @test */
    public function it_confirms_the_running_in_console()
    {
        $this->assertTrue(
            $this->app->getContainer()->runningInConsole()
        );
    }

    /** @test */
    public function it_has_namespace_getter()
    {
        $this->assertEquals($this->app->getContainer()->getNamespace(), 'App\\');
    }

    /** @test */
    public function it_confirms_that_is_not_down_for_maintenance()
    {
        $this->assertFalse(
            $this->app->getContainer()->isDownForMaintenance()
        );
    }

    /** @test */
    public function it_can_abort(): void
    {
        try {
            $this->app->getContainer()->abort(404, 'Foo');
        } catch (CommandNotFoundException $notFoundException) {
        }

        $this->assertInstanceOf(CommandNotFoundException::class, $notFoundException);
        $this->assertEquals($notFoundException->getMessage(), 'Foo');

        try {
            abort(200, 'Bar', ['Foo' => 'Bar']);
        } catch (ConsoleException $consoleException) {
        }

        $this->assertInstanceOf(ConsoleException::class, $consoleException);
        $this->assertEquals($consoleException->getExitCode(), 200);
        $this->assertEquals($consoleException->getMessage(), 'Bar');
        $this->assertEquals($consoleException->getHeaders(), ['Foo' => 'Bar']);
    }

    /** @test */
    public function it_haves_an_environment_path(): void
    {
        $this->assertSame($this->app->getContainer()->environmentPath(), base_path());

        $this->app->getContainer()->useEnvironmentPath('foo');

        $this->assertSame($this->app->getContainer()->environmentPath(), 'foo');
    }

    /** @test */
    public function it_haves_an_environment_file(): void
    {
        $this->assertSame($this->app->getContainer()->environmentFile(), '.env');

        $this->app->getContainer()->loadEnvironmentFrom('foo');

        $this->assertSame($this->app->getContainer()->environmentFile(), 'foo');
    }

    /** @test */
    public function it_get_an_environment_file_path(): void
    {
        $this->assertSame($this->app->getContainer()->environmentFilePath(), base_path('.env'));
    }

    /** @test */
    public function it_dont_cache_configuration(): void
    {
        $this->assertFalse($this->app->getContainer()->configurationIsCached());
    }

    /** @test */
    public function it_dont_implement_method_register_configured_providers()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->registerConfiguredProviders();
    }

    /** @test */
    public function it_dont_implement_method_register()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->register('foo');
    }

    /** @test */
    public function it_dont_implement_method_register_deferred_provider()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->registerDeferredProvider('foo');
    }

    /** @test */
    public function it_dont_implement_method_register_boot()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->boot();
    }

    /** @test */
    public function it_dont_implement_method_register_booting()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->booting('foo');
    }

    /** @test */
    public function it_dont_implement_method_register_booted()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->booted('foo');
    }

    /** @test */
    public function it_dont_implement_method_get_cached_services_path()
    {
        $this->expectException(NotImplementedException::class);

        $this->app->getContainer()->getCachedServicesPath();
    }

    /** @test */
    public function it_configures_monolog()
    {
        $container = $this->app->getContainer();
        $callback = function () {
        };

        $this->assertFalse($container->hasMonologConfigurator());
        $this->app->getContainer()->configureMonologUsing($callback);
        $this->assertTrue($container->hasMonologConfigurator());
        $this->assertSame($container->getMonologConfigurator(), $callback);
    }
}
