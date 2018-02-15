<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Log;

use LaravelZero\Framework\Components\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework Log Component Provider Implementation.
 */
class Provider extends AbstractComponentProvider
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

        $config = $this->app['config'];

        $config->set('logging.default', $config->get('logging.default') ?: 'default');
    }
}
