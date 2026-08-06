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

namespace LaravelZero\Framework\Components\View;

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
    protected $name = 'install:view';

    /**
     * {@inheritdoc}
     */
    protected $description = 'View: Blade View Components';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'view.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('illuminate/view "^12.17"');

        $this->task(
            'Creating resources/views folder',
            function () {
                if (! File::exists($this->app->resourcePath('views'))) {
                    File::makeDirectory($this->app->resourcePath('views'), 0755, true, true);

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Creating default view configuration',
            function () {
                if (! File::exists($this->app->configPath('view.php'))) {
                    return File::copy(
                        static::CONFIG_FILE,
                        $this->app->configPath('view.php')
                    );
                }

                return false;
            }
        );

        $this->task(
            'Creating cache storage folder',
            function () {
                if (File::exists($this->app->storagePath('app/.gitignore')) &&
                    File::exists($this->app->storagePath('framework/views/.gitignore'))
                ) {
                    return false;
                }

                if (! File::exists($this->app->storagePath('app'))) {
                    File::makeDirectory($this->app->storagePath('app'), 0755, true, true);
                }

                if (! File::exists($this->app->storagePath('app/.gitignore'))) {
                    File::append($this->app->storagePath('app/.gitignore'), "*\n!.gitignore");
                }

                if (! File::exists($this->app->storagePath('framework/views'))) {
                    File::makeDirectory($this->app->storagePath('framework/views'), 0755, true, true);
                }

                if (! File::exists($this->app->storagePath('framework/views/.gitignore'))) {
                    File::append($this->app->storagePath('framework/views/.gitignore'), "*\n!.gitignore");
                }

                return true;
            }
        );
    }
}
