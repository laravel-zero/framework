<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Log;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework Log Component Provider Implementation.
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
        $this->app->register(\Illuminate\Log\LogServiceProvider::class);

        $this->app->alias('log', \Illuminate\Log\Writer::class);
        $this->app->alias('log', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('log', \Psr\Log\LoggerInterface::class);
    }
}
