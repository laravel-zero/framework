<?php

namespace LaravelZero\Framework\Commands\Component\Illuminate\Filesystem;

use LaravelZero\Framework\Commands\Component\AbstractComponentProvider;

/**
 * This is the Laravel Zero Framework illuminate/Filesystem component provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ComponentProvider extends AbstractComponentProvider
{
    /**
     * {@inheritdoc}
     */
    public function isAvailable(): bool
    {
        return class_exists(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->registerServiceProvider(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }
}
