<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use LaravelZero\Framework\Container;

class ContainerTest extends TestCase
{
    /** @test */
    public function it_has_a_base_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH, $container->basePath());
        $this->assertEquals(BASE_PATH.'/Unit', $container->basePath('Unit'));
    }

    /** @test */
    public function it_has_a_config_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH.'/config', $container->configPath());
        $this->assertEquals(BASE_PATH.'/config/custom.php', $container->configPath('custom.php'));
    }

    /** @test */
    public function it_has_a_database_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH.'/database', $container->databasePath());
        $this->assertEquals(BASE_PATH.'/database/migrations', $container->databasePath('migrations'));

        // config settings take precedence
        config(['database.path' => 'some/other/path']);
        $this->assertEquals('some/other/path', $container->databasePath());
    }

    /** @test */
    public function it_has_a_lang_path_getter()
    {
        $this->assertEquals(BASE_PATH.'/resources/lang', (new Container)->langPath());
    }

    /** @test */
    public function it_has_a_storage_path_getter()
    {
        $this->assertEquals(BASE_PATH.'/storage', (new Container)->storagePath());
    }

    /** @test */
    public function it_has_a_resource_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH.'/resources', $container->resourcePath());
        $this->assertEquals(BASE_PATH.'/resources/assets/js', $container->resourcePath('assets/js'));
        $this->assertEquals(BASE_PATH.'/resources/assets/js/app.php', $container->resourcePath('assets/js/app.php'));
    }
}
