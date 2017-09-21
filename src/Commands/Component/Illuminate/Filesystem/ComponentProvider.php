<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Filesystem;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Zero Framework illuminate/Filesystem component provider class.
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
        if (! class_exists(\Illuminate\Filesystem\FilesystemServiceProvider::class)) {
            return;
        }

        $this->registerServiceProvider(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }
}
