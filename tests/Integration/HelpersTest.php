<?php

namespace Tests\Integration;

use Tests\TestCase;
use LaravelZero\Framework\Container;

class HelpersTest extends TestCase
{
    /** @test */
    public function app_helper(): void
    {
        $this->assertSame(app(), Container::getInstance());

        Container::getInstance()->bind('foo', function () {
            return 'bar';
        });

        $this->assertEquals(app('foo'), 'bar');
    }

    /** @test */
    public function app_path_helper(): void
    {
        $this->assertSame(app_path(), app('path'));

        $this->assertEquals(app_path('foo'), app('path').DIRECTORY_SEPARATOR.'foo');
    }

    /** @test */
    public function config_helper(): void
    {
        $this->assertSame(config(), app('config'));

        config(['foo' => 'bar']);

        $this->assertEquals(app('config')->get('foo'), 'bar');

        $this->assertEquals(app('config')->get('foo2', 2), 2);
    }

    /** @test */
    public function event_helper(): void
    {
        // @todo ...
    }

    /** @test */
    public function base_path_helper(): void
    {
        $this->assertEquals(BASE_PATH, base_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'some'.DIRECTORY_SEPARATOR.'directory',
            base_path('some'.DIRECTORY_SEPARATOR.'directory')
        );
    }

    /** @test */
    public function config_path_helper(): void
    {
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'config', config_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'vendor',
            config_path('vendor')
        );
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'custom.php',
            config_path('custom.php')
        );
    }

    /** @test */
    public function database_path_helper(): void
    {
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'database', database_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeds',
            database_path('seeds')
        );
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php',
            database_path('seeds'.DIRECTORY_SEPARATOR.'DatabaseSeeder.php')
        );
    }

    /** @test */
    public function resource_path_helper(): void
    {
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'resources', resource_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js',
            resource_path('assets'.DIRECTORY_SEPARATOR.'js')
        );
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'app.php',
            resource_path('assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'app.php')
        );
    }

    /** @test */
    public function storage_path_helper(): void
    {
        $this->assertEquals(BASE_PATH.DIRECTORY_SEPARATOR.'storage', storage_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs',
            storage_path('logs')
        );
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'laravel.log',
            storage_path('logs'.DIRECTORY_SEPARATOR.'laravel.log')
        );
    }
}
