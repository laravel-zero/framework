<?php

namespace LaravelZero\Framework\Bootstrappers;

use LaravelZero\Framework\Providers;
use Illuminate\Events\EventServiceProvider;
use LaravelZero\Framework\Commands\Component;
use NunoMaduro\LaravelDesktopNotifier\LaravelDesktopNotifierServiceProvider;

/**
 * This is the Laravel Zero Framework Bootstrapper ServiceProviders class.
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
        Providers\ErrorHandler\ServiceProvider::class,
        EventServiceProvider::class,
        Providers\Cache\ServiceProvider::class,
        Providers\Composer\ServiceProvider::class,
        Providers\Scheduler\ServiceProvider::class,
        Providers\Filesystem\ServiceProvider::class,
        LaravelDesktopNotifierServiceProvider::class,
    ];

    /**
     * The application's core components.
     *
     * @var string[]
     */
    protected $components = [
        Component\Illuminate\Log\ComponentProvider::class,
        Component\Illuminate\Database\ComponentProvider::class,
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
        'cache' => [\Illuminate\Cache\CacheManager::class, \Illuminate\Contracts\Cache\Factory::class],
        'cache.store' => [\Illuminate\Cache\Repository::class, \Illuminate\Contracts\Cache\Repository::class],
        'files' => [\Illuminate\Filesystem\Filesystem::class],
        'filesystem' => [
            \Illuminate\Filesystem\FilesystemManager::class,
            \Illuminate\Contracts\Filesystem\Factory::class,
        ],
        'filesystem.disk' => [\Illuminate\Contracts\Filesystem\Filesystem::class],
        'filesystem.cloud' => [\Illuminate\Contracts\Filesystem\Cloud::class],
    ];

    /**
     * {@inheritdoc}
     */
    public function bootstrap(): void
    {
        $providers = collect($this->providers)
            ->merge(
                collect($this->components)
                    ->filter(
                        function ($component) {
                            return (new $component($this->application))->isAvailable();
                        }
                    )
                    ->toArray()
            )
            ->merge(
                $this->container->make('config')
                    ->get('app.providers')
            );

        $providers->each(
            function ($serviceProviderClass) {
                $this->call($serviceProviderClass, 'register');
            }
        );

        foreach ($this->aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->container->alias($key, $alias);
            }
        }

        $providers->each(
            function ($serviceProviderClass) {
                $this->call($serviceProviderClass, 'boot');
            }
        );
    }

    /**
     * Creates a new instance of the service provider an
     * calls the provided method.
     *
     * @param string $serviceProviderClass
     * @param string $method
     */
    private function call(string $serviceProviderClass, string $method): void
    {
        $provider = new $serviceProviderClass($this->container);
        if (method_exists($provider, $method)) {
            $this->container->call([$provider, $method]);
        }
    }
}
