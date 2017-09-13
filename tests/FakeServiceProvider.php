<?php

namespace Tests;

use Illuminate\Support\ServiceProvider;

/**
 * This is the Zero Framework composer service provider class.
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
