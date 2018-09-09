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

namespace LaravelZero\Framework\Providers\Collision;

use NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider as BaseCollisionServiceProvider;

/**
 * @internal
 */
final class CollisionServiceProvider extends BaseCollisionServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        if (! $this->app->environment('production')) {
            $this->app->register(BaseCollisionServiceProvider::class);
        }
    }
}
