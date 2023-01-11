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

namespace LaravelZero\Framework\Components\Queue;

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
    protected $name = 'install:queue';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Queues: Unified API across a variety of queue services';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'queue.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->call('app:install', ['component' => 'database']);

        $this->require('illuminate/bus "^10.0"');
        $this->require('illuminate/queue "^10.0"');

        $this->task(
            'Creating default queue configuration',
            function () {
                if (! File::exists(config_path('queue.php'))) {
                    return File::copy(
                        self::CONFIG_FILE,
                        $this->app->configPath('queue.php')
                    );
                }

                return false;
            }
        );
    }
}
