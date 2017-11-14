<?php

namespace Tests\Integration;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function base_path_helper_function()
    {
        $this->assertEquals(BASE_PATH, base_path());
        $this->assertEquals(BASE_PATH.'/some/directory', base_path('some/directory'));
    }

    /** @test */
    public function config_path_helper_function()
    {
        $this->assertEquals(BASE_PATH.'/config', config_path());
        $this->assertEquals(BASE_PATH.'/config/vendor', config_path('vendor'));
        $this->assertEquals(BASE_PATH.'/config/custom.php', config_path('custom.php'));
    }

    /** @test */
    public function database_path_helper_function()
    {
        $this->assertEquals(BASE_PATH.'/database', database_path());
        $this->assertEquals(BASE_PATH.'/database/seeds', database_path('seeds'));
        $this->assertEquals(BASE_PATH.'/database/seeds/DatabaseSeeder.php', database_path('seeds/DatabaseSeeder.php'));
    }

    /** @test */
    public function resource_path_helper_function()
    {
        $this->assertEquals(BASE_PATH.'/resources', resource_path());
        $this->assertEquals(BASE_PATH.'/resources/assets/js', resource_path('assets/js'));
        $this->assertEquals(BASE_PATH.'/resources/assets/js/app.php', resource_path('assets/js/app.php'));
    }

    /** @test */
    public function storage_path_helper_function()
    {
        $this->assertEquals(BASE_PATH.'/storage', storage_path());
        $this->assertEquals(BASE_PATH.'/storage/logs', storage_path('logs'));
        $this->assertEquals(BASE_PATH.'/storage/logs/laravel.log', storage_path('logs/laravel.log'));
    }
}
