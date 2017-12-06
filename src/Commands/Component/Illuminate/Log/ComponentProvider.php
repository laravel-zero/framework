<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Log;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework illuminate/log component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ComponentProvider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Log\LogServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->registerServiceProvider(\Illuminate\Log\LogServiceProvider::class);

        $this->app->alias('log', \Illuminate\Log\Writer::class);
        $this->app->alias('log', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('log', \Psr\Log\LoggerInterface::class);
    }
}
