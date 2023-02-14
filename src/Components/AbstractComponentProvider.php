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

namespace LaravelZero\Framework\Components;

use Illuminate\Support\ServiceProvider;

/**
 * @internal
 */
abstract class AbstractComponentProvider extends ServiceProvider
{
    /**
     * Registers the component on the application.
     *
     * Should adapt the service to the console/cli context.
     */
    public function register(): void
    {
        // ..
    }

    /**
     * Checks if the component is available.
     */
    abstract public function isAvailable(): bool;
}
