<?php

declare(strict_types=1);

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Components\Log;

use Illuminate\Contracts\Config\Repository;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;

/**
 * @internal
 */
final class Provider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Log\LogServiceProvider::class)
            && $this->app['config']->get('logging.useDefaultProvider', true) === true;
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->register(\Illuminate\Log\LogServiceProvider::class);

        /** @var Repository $config */
        $config = $this->app['config'];

        $config->set('logging.default', $config->get('logging.default') ?: 'default');
    }
}
