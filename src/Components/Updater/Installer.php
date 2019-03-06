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

namespace LaravelZero\Framework\Components\Updater;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

/**
 * @internal
 */
final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:updater';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Updater: Self update your APP phar file';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'updater.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('padraic/phar-updater "^1.0.6"');

        $this->task(
            'Creating default self update configuration',
            function () {
                if (! File::exists(config_path('updater.php'))) {
                    return File::copy(
                        static::CONFIG_FILE,
                        $this->app->configPath('updater.php')
                    );
                }

                return false;
            }
        );
    }
}
