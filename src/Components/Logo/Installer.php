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

namespace LaravelZero\Framework\Components\Logo;

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
    protected $name = 'install:logo';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Logo: Display app name as ASCII logo';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'logo.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('laminas/laminas-text "^2.10"');

        $this->task(
            'Creating default logo configuration',
            function () {
                if (! File::exists(config_path('logo.php'))) {
                    return File::copy(
                        static::CONFIG_FILE,
                        $this->app->configPath('logo.php')
                    );
                }

                return false;
            }
        );
    }
}
