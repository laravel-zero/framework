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

namespace LaravelZero\Framework\Components\Redis;

use Illuminate\Redis\RedisServiceProvider;
use LaravelZero\Framework\Components\AbstractComponentProvider;

use function class_exists;

/** @internal */
final class Provider extends AbstractComponentProvider
{
    /** {@inheritdoc} */
    public function isAvailable(): bool
    {
        return class_exists(RedisServiceProvider::class);
    }

    /** {@inheritdoc} */
    public function register(): void
    {
        $this->app->register(RedisServiceProvider::class);
    }
}
