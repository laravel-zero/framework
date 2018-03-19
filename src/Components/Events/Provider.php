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
        return  class_exists(\Illuminate\Events\EventServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        // Since the LaravelZero Framework requires illuminate\events be installed, the call to isAvailable() above
        // will always return true. We cannot put this particular conditional in the isAvailable method since the
        // application isn't fully booted, and we cannot resolve $this->app->getNamespace(). Only when the
        // application-level EventServiceProvider is present do we register the Framework's  Provider.
        $providerName = $this->app->getNamespace().'Providers\EventServiceProvider';
        if (class_exists($providerName)) {
            $this->app->register($providerName);
        }
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
