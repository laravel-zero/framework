<?php

namespace LaravelZero\Framework\Commands\Component;

use Illuminate\Support\ServiceProvider;

/**
 * This is the Zero Framework illuminate/database component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
abstract class AbstractComponentProvider extends ServiceProvider
{
    /**
     * Registers the component on the application.
     *
     * Should adapt the service to the console/cli context.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Register the service provider.
     *
     * @param string $providerClass
     *
     * @return void
     */
    protected function registerServiceProvider(string $providerClass): void
    {
        $instance = new $providerClass($this->app);
        if (method_exists($instance, 'register')) {
            $instance->register();
        }

        if (method_exists($instance, 'boot')) {
            $instance->boot();
        }
    }
}
