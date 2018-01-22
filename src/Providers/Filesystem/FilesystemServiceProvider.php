<?php

namespace LaravelZero\Framework\Providers\Filesystem;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider as BaseServiceProvider;

/**
 * This is the Laravel Zero Framework Filesystem service provider class.
 *
 * @author Nuno Maduro <enunomaduro@gmail.com>
 */
class FilesystemServiceProvider extends BaseServiceProvider
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
            $config->set('filesystems', $this->getDefaultConfig());
        }
    }

    /**
     * Returns the default application filesystems config.
     *
     * In order to keep it simple we use the `local` driver. Feel free
     * to use another driver, be sure to check the filesysyem
     * component documentation.
     *
     * @return array
     */
    protected function getDefaultConfig(): array
    {
        return [
            'default' => 'local',
            'disks' => [
                'local' => [
                    'driver' => 'local',
                    'root' => storage_path('app'),
                ],
            ],
        ];
    }
}
