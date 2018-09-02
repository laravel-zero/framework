<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * This is the Laravel Zero Framework fake service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class FakeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'foo',
            function () {
                return 'bar';
            }
        );
    }
}
