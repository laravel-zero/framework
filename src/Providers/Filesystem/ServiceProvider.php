<?php

namespace LaravelZero\Framework\Providers\Filesystem;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider as BaseServiceProvider;

/**
 * This is the Laravel Zero Framework Filesystem service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register Scheduler service.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->app->alias('filesystem.disk', Filesystem::class);

        $config = $this->app->make('config');

        if ($config->get('filesystems.default') === null) {
            $config->set('filesystems.default', 'local');
        }
    }
}
