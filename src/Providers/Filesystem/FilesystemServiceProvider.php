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

namespace LaravelZero\Framework\Providers\Filesystem;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemServiceProvider as BaseServiceProvider;

/**
 * @internal
 */
final class FilesystemServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
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
