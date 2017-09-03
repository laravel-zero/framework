<?php

/**
 * This file is part of Zero Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * The is the Zero Framework illuminate/database component provider class.
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
        if (! class_exists(\Illuminate\Database\DatabaseServiceProvider::class)) {
            return;
        }

        $this->registerServiceProvider(\Illuminate\Database\DatabaseServiceProvider::class);

        // Make this Capsule instance available globally via static methods
        $this->app->make(\Illuminate\Database\Capsule\Manager::class)
            ->setAsGlobal();
    }
}
