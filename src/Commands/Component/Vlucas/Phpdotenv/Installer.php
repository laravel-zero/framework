<?php

/**
 * This file is part of Laravel Zero.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace LaravelZero\Framework\Commands\Component\Vlucas\Phpdotenv;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Component\AbstractInstaller;

/**
 * This is the Laravel Zero Framework Dot Env Component Installer Implementation.
 */
class Installer extends AbstractInstaller
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'install:dotenv';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Installs vlucas/phpdotenv';

    /**
     * {@inheritdoc}
     */
    public function getComponentName(): string
    {
        return 'dotenv';
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        $this->require('vlucas/phpdotenv');

        $this->task(
            'Creating .env',
            function () {
                if (! File::exists(base_path('.env'))) {
                    return File::put(base_path('.env'), 'CONSUMER_KEY=');
                }

                return false;
            }
        );

        $this->task(
            'Creating .env.example',
            function () {
                if (! File::exists(base_path('.env.example'))) {
                    return File::put(base_path('.env.example'), 'CONSUMER_KEY=');
                }

                return false;
            }
        );

        $this->task('Updating .gitignore', function () {
            $gitignorePath = base_path('.gitignore');
            if (File::exists($gitignorePath)) {
                $contents = File::get($gitignorePath);
                $neededLine = '.env';
                if (! Str::contains($contents, $neededLine)) {
                    File::append($gitignorePath, $neededLine);
                    return true;
                }
            }

            return false;
        });

        return true;
    }
}
