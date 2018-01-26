<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Providers\Filesystem;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider as BaseServiceProvider;

/**
 * This is the Laravel Zero Framework Filesystem Service Provider implementation.
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
     * We it simple we use the `local` driver.
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
                    'root' => $this->app->storagePath('app'),
                ],
            ],
        ];
    }
}
