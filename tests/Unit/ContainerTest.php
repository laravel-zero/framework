<?php

namespace Tests\Unit;

use Tests\TestCase;
use LaravelZero\Framework\Container;

class ContainerTest extends TestCase
{
    /** @test */
    public function it_has_a_base_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH, $container->basePath());
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'Unit', $container->basePath('Unit'));
    }

    /** @test */
    public function it_has_a_config_path_getter()
    {
        $container = new Container;

        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'config', $container->configPath());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'custom.php',
            $container->configPath('custom.php')
        );
    }

    /** @test */
    public function it_has_a_database_path_getter()
    {
        $container = new Container;

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
            (new Container)->langPath()
        );
    }

    /** @test */
    public function it_has_a_storage_path_getter()
    {
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'storage', (new Container)->storagePath());
    }

    /** @test */
    public function it_has_a_resource_path_getter()
    {
        $container = new Container;

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
}
