<?php

namespace LaravelZero\Framework\Bootstrappers;

use Illuminate\Container\Container;
use NunoMaduro\LaravelDesktopNotifier\LaravelDesktopNotifierServiceProvider;
use LaravelZero\Framework\Commands\Component;

/**
 * This is the Zero Framework Bootstrapper ServiceProviders class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProviders extends Bootstrapper
{
    /**
     * The application's core providers.
     *
     * @var string[]
     */
    protected $providers = [
        \Illuminate\Events\EventServiceProvider::class,
        \LaravelZero\Framework\Providers\Composer\ServiceProvider::class,
        LaravelDesktopNotifierServiceProvider::class,
    ];

    /**
     * The application's core components.
     *
     * @var string[]
     */
    protected $components = [
        Component\Illuminate\Database\ComponentProvider::class,
        Component\Illuminate\Cache\ComponentProvider::class,
    ];

    /**
     * The application core aliases.
     *
     * @var array
     */
    protected $aliases = [
        'app' => [\Illuminate\Contracts\Container\Container::class],
        'events' => [\Illuminate\Events\Dispatcher::class, \Illuminate\Contracts\Events\Dispatcher::class],
        'config' => [\Illuminate\Config\Repository::class, \Illuminate\Contracts\Config\Repository::class],
    ];

    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        collect($this->providers)
            ->merge($this->components)
            ->merge($this->container->make('config')->get('app.providers'))
            ->each(function ($serviceProvider) {
                $instance = new $serviceProvider($this->application);
                if (method_exists($instance, 'register')) {
                    $instance->register();
                }

                if (method_exists($instance, 'boot')) {
                    $instance->boot();
                }
            }
        );

        foreach ($this->aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->container->alias($key, $alias);
            }
        }
    }
}
