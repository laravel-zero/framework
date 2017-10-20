<?php

namespace LaravelZero\Framework\Providers\Scheduler;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * This is the Laravel Zero Framework Scheduler service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register Scheduler service.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            Schedule::class,
            function ($app) {
                return new Schedule;
            }
        );
    }
}
