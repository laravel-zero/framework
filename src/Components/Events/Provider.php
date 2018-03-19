<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Events;

use LaravelZero\Framework\Components\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework Events Component Provider Implementation.
 */
class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists($this->app->getNamespace() . 'Providers\EventServiceProvider');
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register($this->app->getNamespace() . 'Providers\EventServiceProvider');
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        if ($this->app->environment() !== 'production') {
            $this->commands([\Illuminate\Foundation\Console\EventGenerateCommand::class]);
            $this->commands([\Illuminate\Foundation\Console\EventMakeCommand::class]);
            $this->commands([\Illuminate\Foundation\Console\ListenerMakeCommand::class]);
        }
    }
}
