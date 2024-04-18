<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
