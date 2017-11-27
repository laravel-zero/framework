<?php

namespace Tests\Integration;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function base_path_helper_function()
    {
        $this->assertEquals(BASE_PATH, base_path());
        $this->assertEquals(
            BASE_PATH.DIRECTORY_SEPARATOR.'some'.DIRECTORY_SEPARATOR.'directory',
            base_path('some'.DIRECTORY_SEPARATOR.'directory')
        );
    }

    /** @test */
    public function config_path_helper_function()
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
    public function database_path_helper_function()
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
    public function resource_path_helper_function()
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
    public function storage_path_helper_function()
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
