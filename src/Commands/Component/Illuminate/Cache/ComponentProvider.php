<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Cache;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Zero Framework illuminate/cache component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ComponentProvider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        if (! class_exists(\Illuminate\Cache\CacheServiceProvider::class)) {
            return;
        }

        $this->registerServiceProvider(\Illuminate\Cache\CacheServiceProvider::class);

        $this->app->alias('cache', \Illuminate\Cache\CacheManager::class);
        $this->app->alias('cache', \Illuminate\Contracts\Cache\Factory::class);
        $this->app->alias('cache.store', \Illuminate\Cache\Repository::class);
        $this->app->alias('cache.store', \Illuminate\Contracts\Cache\Repository::class);
        $this->app->make('config')->set('cache', [
            'default' => 'array',
            'stores' => [
                'array' => [
                    'driver' => 'array',
                ],
            ],
        ]);
    }
}
