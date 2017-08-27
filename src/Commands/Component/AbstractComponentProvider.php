<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace NunoMaduro\ZeroFramework\Commands\Component;

use Illuminate\Support\ServiceProvider;

/**
 * The is the Zero Framework illuminate/database component provider class.
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
