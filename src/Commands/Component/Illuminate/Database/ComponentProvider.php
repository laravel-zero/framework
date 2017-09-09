<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Database;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Zero Framework illuminate/database component provider class.
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
