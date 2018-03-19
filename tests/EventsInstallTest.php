<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class EventsInstallTest extends TestCase
{
    public function tearDown()
    {
        File::delete($this->app->basePath('app').'/Providers/EventServiceProvider.php');
        File::delete($this->app->basePath('app').'/Events/TestEvent.php');
        File::delete($this->app->basePath('app').'/Listeners/TestEventListener.php');
    }

    /** @test */
    public function it_copies_the_stub(): void
    {
        Artisan::call('app:install', ['component' => 'events']);

        $this->assertTrue(File::exists($this->app->basePath('app').'/Providers/EventServiceProvider.php'));
    }

    /** @test */
    public function it_enables_make_event_command(): void
    {
        Artisan::call('app:install', ['component' => 'events']);
        Artisan::call('make:event', ['name' => 'TestEvent']);
        $this->assertTrue(File::exists($this->app->basePath('app').'/Events/TestEvent.php'));
    }

    /** @test */
    public function it_enables_make_listener_command(): void
    {
        Artisan::call('app:install', ['component' => 'events']);
        Artisan::call('make:listener', ['name' => 'TestEventListener']);
        $this->assertTrue(File::exists($this->app->basePath('app').'/Listeners/TestEventListener.php'));
    }

    /** @test */
    public function it_enables_event_generate_command(): void
    {
        Artisan::call('app:install', ['component' => 'events']);
        $result = Artisan::call('event:generate');
        $this->assertEquals(0, $result);
    }
}
