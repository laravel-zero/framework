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
        $this->require('illuminate/view "^10.0"');

        $this->task(
            'Creating resources/views folder',
            function () {
                if (! File::exists(base_path('resources/views'))) {
                    File::makeDirectory(base_path('resources/views'), 0755, true, true);

                    return true;
                }

                return false;
            }
        );

        $this->task(
            'Creating default view configuration',
            function () {
                if (! File::exists(config_path('view.php'))) {
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
                if (File::exists(base_path('storage/app/.gitignore')) &&
                    File::exists(base_path('storage/framework/views/.gitignore'))
                ) {
                    return false;
                }

                if (! File::exists(base_path('storage/app'))) {
                    File::makeDirectory(base_path('storage/app'), 0755, true, true);
                }

                if (! File::exists(base_path('storage/app/.gitignore'))) {
                    File::append(base_path('storage/app/.gitignore'), "*\n!.gitignore");
                }

                if (! File::exists(base_path('storage/framework/views'))) {
                    File::makeDirectory(base_path('storage/framework/views'), 0755, true, true);
                }

                if (! File::exists(base_path('storage/framework/views/.gitignore'))) {
                    File::append(base_path('storage/framework/views/.gitignore'), "*\n!.gitignore");
                }

                return true;
            }
        );
    }
}
