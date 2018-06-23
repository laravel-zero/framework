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

namespace LaravelZero\Framework\Components\Log;

use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Components\AbstractInstaller;

final class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:log';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs illuminate/log';

    /**
     * The config file path.
     */
    private const CONFIG_FILE = __DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'logging.php';

    /**
     * {@inheritdoc}
     */
    public function install(): void
    {
        $this->require('illuminate/log "5.6.*"');

        $this->task(
            'Creating default logging configuration',
            function () {
                if (! File::exists(config_path('logging.php'))) {
                    return File::copy(
                        static::CONFIG_FILE,
                        $this->app->configPath('logging.php')
                    );
                }

                return false;
            }
        );

        $this->info('Usage:');
        $this->comment(
            '
use Illuminate\Support\Facades\Log;

Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
'
        );
    }
}
