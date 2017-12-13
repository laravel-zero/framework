<?php

namespace Tests\Application;

use Illuminate\Support\ServiceProvider;

/**
 * This is the Laravel Zero Framework fake service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class FakeServiceProvider extends ServiceProvider
{
    /**
     * Register composer service.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            'foo',
            function () {
                return 'bar';
            }
        );
    }
}
