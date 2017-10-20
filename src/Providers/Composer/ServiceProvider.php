<?php

namespace LaravelZero\Framework\Providers\Composer;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use LaravelZero\Framework\Contracts\Providers\Composer as ComposerContract;

/**
 * This is the Laravel Zero Framework composer service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register composer service.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            'composer',
            function ($app) {
                return new Composer;
            }
        );

        $this->app->alias('composer', ComposerContract::class);
    }
}
